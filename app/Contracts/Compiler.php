<?php namespace Judge\Contracts;

    /**
     * This file belongs to judge.
     *
     * Author: Rahul Kadyan, <hi@znck.me>
     * Find license in root directory of this project.
     */
/**
 * Interface Compiler
 *
 * @package Judge\Contracts
 */
interface Compiler
{
    /**
     * @param string $source source code file
     * @param array  $output
     * @param int    $exit_code
     *
     * @return string compiled executable file
     */
    public function compile($source, &$output, &$exit_code);

    /**
     * @param string $output
     *
     * @return void
     */
    public function setOutputFile($output);

    /**
     * @return string
     */
    public function getOutputFile();
}