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
     * @param string $binary executable file
     * @param string $input  input text (input for stdin)
     * @param string $output output text (output from stdout)
     * @param string $errors error text (output from stderr)
     *
     * @return int exit code
     */
    public function execute($binary, $input, &$output, &$errors = null);
}