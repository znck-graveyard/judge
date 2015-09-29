<?php

namespace Judge\Jobs;

use Event;
use Illuminate\Contracts\Bus\SelfHandling;
use Judge\Events\Compile\Failed;
use Judge\Events\Compile\Running as Compiling;
use Judge\Events\Compile\Success;
use Judge\Events\Test\Failed as TestFailed;
use Judge\Events\Test\Running as RunningTest;
use Judge\Events\Test\Success as TestSuccess;
use Judge\Exceptions\CompilationFailedException;
use Judge\Factories\JudgeFactory;
use Judge\Problems\Problem;
use Judge\Problems\Solution;
use Judge\User;

/**
 * Class RunTestCases
 *
 * @package Judge\Jobs
 */
class RunTestCases extends Job implements SelfHandling
{
    /**
     * @type \Judge\User
     */
    protected $user;
    /**
     * @type \Judge\Problems\Problem
     */
    protected $problem;
    /**
     * @type \Judge\Problems\Solution
     */
    protected $solution;
    /**
     * @type \Judge\Factories\JudgeFactory
     */
    protected $factory;

    /**
     * Create a new job instance.
     *
     * @param \Judge\User                   $user
     * @param \Judge\Problems\Problem       $problem
     * @param \Judge\Problems\Solution      $solution
     * @param \Judge\Factories\JudgeFactory $factory
     */
    public function __construct(User $user, Problem $problem, Solution $solution, JudgeFactory $factory)
    {
        $this->user = $user;
        $this->problem = $problem;
        $this->solution = $solution;
        $this->factory = $factory;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->factory->with($this->solution->language);
        $compiler = $this->factory->getCompiler();
        $runner = $this->factory->getRunner();
        $status = $errors = '';
        $executable = '';

        Event::fire(new Compiling($this->user, $this->solution));
        try {
            $executable = $compiler->compile($this->solution->source, $errors, $status);
        } catch (CompilationFailedException $e) {
            Event::fire(new Failed($this->user, $this->solution, $errors, $status));
        }

        if ($status !== 0) {
            Event::fire(new Failed($this->user, $this->solution, $errors, $status));

            return;
        } else {
            Event::fire(new Success($this->user, $this->solution, $errors, $status));
        }

        foreach ($this->problem->testCases as $testCase) {
            Event::fire(new RunningTest($this->user, $this->solution, $testCase));
            $output = $errors = '';
            $runner->execute($executable, $testCase->input, $output, $errors);
            if (0 === strcmp(trim($output), $testCase->output)) {
                Event::fire(new TestSuccess($this->user, $this->solution, $testCase, $output, $errors));
            } else {
                Event::fire(new TestFailed($this->user, $this->solution, $testCase, $output, $errors));
            }
        }
    }
}
