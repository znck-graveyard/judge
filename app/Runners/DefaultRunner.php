<?php namespace Judge\Runners;

use Judge\Contracts\Runner;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class DefaultRunner extends AbstractRunner implements Runner
{
    /**
     * @param string $input  input text (input for stdin)
     * @param string $output output text (output from stdout)
     * @param string $errors error text (output from stderr)
     *
     * @return int exit code
     */
    public function execute($input, &$output, &$errors = null)
    {
        return $this->executeCommand($this->binary, $input, $output, $errors);
    }
}