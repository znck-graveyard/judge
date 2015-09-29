<?php namespace Judge\Contracts;

    /**
     * This file belongs to judge.
     *
     * Author: Rahul Kadyan, <hi@znck.me>
     * Find license in root directory of this project.
     */
/**
 * Interface Runner
 *
 * @package Judge\Contracts
 */
interface Runner
{
    /**
     * @param \Judge\Contracts\Compiler $compiler
     *
     * @return void
     */
    public function setCompiler(Compiler $compiler);

    /**
     * @return \Judge\Contracts\Compiler
     */
    public function getCompiler();

    /**
     * @param string $source source code file
     * @param array  $errors
     * @param int    $statusCode
     *
     * @return string compiled executable file
     */
    public function compile($source, &$errors = null, &$statusCode = null);

    /**
     * @param string $input  input text (input for stdin)
     * @param string $output output text (output from stdout)
     * @param string $errors error text (output from stderr)
     *
     * @return int exit code
     */
    public function execute($input, &$output, &$errors = null);

    /**
     * @return double
     */
    public function runTime();
}