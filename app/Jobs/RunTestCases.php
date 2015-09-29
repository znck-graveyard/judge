<?php

namespace Judge\Jobs;

use Event;
use Illuminate\Contracts\Bus\SelfHandling;
use Judge\Contracts\Runner;
use Judge\Events\Compile\Failed;
use Judge\Events\Compile\Running as Compiling;
use Judge\Events\Compile\Success;
use Judge\Events\Test\Running as RunningTest;
use Judge\Events\Test\Success as TestSuccess;
use Judge\Events\Test\TimeLimit;
use Judge\Exceptions\TimeLimitExceededException;
use Judge\Problems\Problem;
use Judge\Problems\Solution;
use Judge\User;

class RunTestCases extends Job implements SelfHandling
{
    /**
     * @type \Judge\User
     */
    private $user;
    /**
     * @type \Judge\Contracts\Runner
     */
    private $runner;
    /**
     * @type \Judge\Problems\Problem
     */
    private $problem;
    /**
     * @type \Judge\Problems\Solution
     */
    private $solution;

    /**
     * Create a new job instance.
     *
     * @param \Judge\User              $user
     * @param \Judge\Problems\Problem  $problem
     * @param \Judge\Problems\Solution $solution
     * @param \Judge\Contracts\Runner  $runner
     */
    public function __construct(User $user, Problem $problem, Solution $solution, Runner $runner)
    {
        $this->runner = $runner;
        $this->user = $user;
        $this->problem = $problem;
        $this->solution = $solution;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Event::fire(new Compiling($this->user, $this->solution));
        $this->runner->compile($this->source, $errors, $status);

        if ($status !== 0) {
            Event::fire(new Failed($this->user, $this->solution, $errors, $status));

            return;
        } else {
            Event::fire(new Success($this->user, $this->solution, $errors, $status));
        }

        foreach ($this->problem->testCases as $testCase) {
            Event::fire(new RunningTest($this->user, $this->solution, $testCase));
            $output = $errors = '';
            try {
                $this->runner->execute($testCase->input, $output, $errors);
                if (0 === strcmp(trim($output), $testCase->output)) {
                    Event::fire(new TestSuccess($this->user, $this->solution, $testCase, $output, $errors));
                } else {
                    Event::fire(new \Judge\Events\Test\Failed($this->user, $this->solution, $testCase, $output,
                        $errors));
                }
            } catch (TimeLimitExceededException $e) {
                Event::fire(new TimeLimit($this->user, $this->solution, $testCase, $output, $errors));
            }
        }
    }
}
