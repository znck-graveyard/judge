<?php namespace Judge\Runners;

use Illuminate\Contracts\Filesystem\Factory;
use Judge\Contracts\Compiler;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
abstract class AbstractRunner
{
    /**
     * @type \Judge\Contracts\Compiler
     */
    protected $compiler;

    /**
     * @type string Executable binary.
     */
    protected $binary;

    /**
     * @type string
     */
    private $sandbox;

    /**
     * @type \Illuminate\Contracts\Filesystem\Factory
     */
    private $filesystem;

    /**
     * @type double
     */
    protected $runTime;

    /**
     * AbstractRunner constructor.
     *
     * @param \Illuminate\Contracts\Filesystem\Factory $filesystem
     */
    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param \Judge\Contracts\Compiler $compiler
     *
     * @return void
     */
    public function setCompiler(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @return \Judge\Contracts\Compiler
     */
    public function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * @param string $source Source code.
     *
     * @param null   $errors
     * @param        $exitCode
     *
     * @return string executable binary.
     */
    public function compile($source, &$errors = null, &$exitCode = nill)
    {
        $this->compiler->setOutputFile($this->absolutePath($this->randomFile()));

        $sourceFile = $this->randomFile();
        $this->storage()->put($sourceFile, $source);

        $this->binary = $this->compiler->compile($this->absolutePath($sourceFile), $errors, $exitCode);

        return $exitCode;
    }

    public function  runTime()
    {
        return $this->runTime;
    }

    /**
     * @param string $command
     * @param string $input
     * @param string $output
     * @param string $errors
     *
     * @return void
     */
    protected function executeCommand($command, $input, &$output, &$errors)
    {
        $inFile = $this->randomFile();
        $outFile = $this->randomFile();
        $errorFile = $this->randomFile();

        $this->storage()->put($inFile, $input);

        $return = $this->doExecute($command, $inFile, $outFile, $errorFile);

        $output = $this->storage()->get($outFile);
        $errors = $this->storage()->get($errorFile);

        return $return;
    }

    /**
     * @return string
     */
    protected function randomFile()
    {
        return "{$this->sandbox}/" . str_random(36);
    }

    /**
     * @return void
     */
    protected function createSandbox()
    {
        $this->sandbox = str_random();
        $this->storage()->makeDirectory($this->sandbox);
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function storage()
    {
        return $this->filesystem->disk('local');
    }

    /**
     * @return void
     */
    protected function destroySandbox()
    {
        $this->storage()->deleteDirectory($this->sandbox);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function absolutePath($filename)
    {
        return $this->storage()->getDriver()->getAdapter()->getPathPrefix() . "/{$filename}";
    }

    /**
     * @param $command
     * @param $inFile
     * @param $outFile
     * @param $errorFile
     *
     * @return mixed
     */
    private function doExecute($command, $inFile, $outFile, $errorFile)
    {
        $inFile = $this->absolutePath($inFile);
        $outFile = $this->absolutePath($outFile);
        $errorFile = $this->absolutePath($errorFile);

        exec("{$command} < {$inFile} > {$outFile} 2> {$errorFile}", $o, $return);

        return $return;
    }
}