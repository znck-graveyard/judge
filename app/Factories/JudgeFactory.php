<?php namespace Judge\Factories;

use Judge\Analyzers\DefaultAnalyzer;
use Judge\Contracts\Sandbox;
use Judge\Exceptions\CompilerNotFoundException;
use Judge\Runners\DefaultRunner;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class JudgeFactory
{
    /**
     * @type \Judge\Contracts\Runner
     */
    protected $runner;
    /**
     * @type \Judge\Contracts\Compiler
     */
    protected $compiler;
    /**
     * @type \Judge\Contracts\Analyzer
     */
    protected $analyzer;
    /**
     * @type \Judge\Contracts\Sandbox
     */
    protected $sandbox;

    /**
     * JudgeFactory constructor.
     *
     * @param \Judge\Contracts\Sandbox $sandbox
     */
    public function __construct(Sandbox $sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @return \Judge\Contracts\Runner
     */
    public function getRunner()
    {
        return $this->runner;
    }

    /**
     * @return \Judge\Contracts\Compiler
     */
    public function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * @return \Judge\Contracts\Analyzer
     */
    public function getAnalyzer()
    {
        return $this->analyzer;
    }

    /**
     * @return Sandbox
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @param $language
     *
     * @return $this
     * @throws \Judge\Exceptions\CompilerNotFoundException
     * @throws \Symfony\Component\Debug\Exception\ClassNotFoundException
     */
    public function with($language)
    {
        $language = strtolower($language);
        /** @type \Judge\Contracts\Runner $runner */
        $this->compiler = config("compilers.{$language}");
        $this->runner = config("runners.{$language}", DefaultRunner::class);
        $this->analyzer = config("analyzers.{$language}", DefaultAnalyzer::class);

        if (!class_exists($this->compiler)) {
            throw new CompilerNotFoundException("No compiler for {$language}");
        }

        if (!class_exists($this->compiler)) {
            throw new ClassNotFoundException("No runner for {$language}");
        }

        if (!class_exists($this->analyzer)) {
            throw new ClassNotFoundException("No analyzer for {$language}");
        }


        $this->analyzer = new $this->analyzer($this->sandbox);
        $this->compiler = new $this->compiler($this->sandbox);
        $this->runner = new $this->runner($this->sandbox, $this->analyzer);


        return $this;
    }
}