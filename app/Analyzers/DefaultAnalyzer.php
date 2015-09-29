<?php namespace Judge\Analyzers;

use Judge\Contracts\Analyzer;
use Judge\Contracts\Sandbox;
use Judge\Environment;
use Znck\Runner\Analysis;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class DefaultAnalyzer implements Analyzer
{
    /**
     * @type \Znck\Runner\Analysis
     */
    protected $analysis;
    /**
     * @type \Judge\Contracts\Sandbox
     */
    private $sandbox;
    /**
     * @type \Judge\Environment
     */
    private $runner;

    /**
     * DefaultAnalyzer constructor.
     *
     * @param \Judge\Contracts\Sandbox $sandbox
     */
    public function __construct(Sandbox $sandbox)
    {
        $this->sandbox = $sandbox;
        $this->runner = app(Environment::class);
    }

    /**
     * @return \Judge\Environment
     */
    public function getEnvironment()
    {
        return $this->runner;
    }

    /**
     * @param \Judge\Environment $runner
     */
    public function setEnvironment(Environment $runner)
    {
        $this->runner = $runner;
    }


    /**
     * @param string $command
     *
     * @return int
     */
    public function analyze($command)
    {
        $binary = $this->runner->command();
        $this->runner->setAnalysis($this->sandbox->newFile());

        chdir($this->sandbox->rootDirectory());
        exec("{$binary} {$command}", $output, $exit_code);

        if (0 == $exit_code) {
            $this->analysis = new Analysis($this->runner->getAnalysis());
        }

        return $exit_code;
    }

    /**
     * @return int
     */
    public function time()
    {
        return $this->analysis->TOTAL_TIME;
    }

    /**
     * @return int
     */
    public function memory()
    {
        return $this->analysis->MEMORY;
    }

    /**
     * @return int
     */
    public function outputSize()
    {
        return $this->analysis->OUTPUT_COUNT;
    }
}