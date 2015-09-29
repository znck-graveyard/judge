<?php namespace Judge\Contracts;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
use Judge\Environment;

/**
 * Interface Analyzer
 *
 * @package Judge\Contracts
 */
interface Analyzer
{
    /**
     * @param string $command
     *
     * @return int
     */
    public function analyze($command);

    /**
     * @return int
     */
    public function time();

    /**
     * @return int
     */
    public function memory();

    /**
     * @return int
     */
    public function outputSize();

    /**
     * @return \Judge\Environment
     */
    public function getEnvironment();

    /**
     * @param \Judge\Environment $runner
     */
    public function setEnvironment(Environment $runner);
}