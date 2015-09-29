<?php namespace Judge\Runners;

use Judge\Contracts\Analyzer;
use Judge\Contracts\Runner;
use Judge\Contracts\Sandbox;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
abstract class AbstractRunner implements Runner
{
    /**
     * @type \Judge\Contracts\Sandbox
     */
    private $sandbox;
    /**
     * @type \Judge\Contracts\Analyzer
     */
    private $analyzer;

    /**
     * AbstractRunner constructor.
     *
     * @param \Judge\Contracts\Sandbox  $sandbox
     * @param \Judge\Contracts\Analyzer $analyzer
     */
    public function __construct(Sandbox $sandbox, Analyzer $analyzer)
    {
        $this->sandbox = $sandbox;
        $this->analyzer = $analyzer;
    }

    /**
     * @param string $binary executable file
     * @param string $input  input text (input for stdin)
     * @param string $output output text (output from stdout)
     * @param string $errors error text (output from stderr)
     *
     * @return int exit code
     */
    public function execute($binary, $input, &$output, &$errors = null)
    {
        $this->sandbox->put('cin.txt', $input);
        $inFile = 'cin.txt';
        $outFile = 'cout.txt';
        $errorFile = 'cerr.txt';

        $return = $this->analyzer->analyze("{$binary} < {$inFile} > {$outFile} 2> {$errorFile}");

        $output = $this->sandbox->get($outFile);
        $errors = $this->sandbox->get($errorFile);

        return $return;
    }
}