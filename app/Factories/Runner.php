<?php namespace Judge\Factories;

use Judge\Exceptions\CompilerNotFoundException;
use Judge\Runners\DefaultRunner;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class Runner
{
    public static function get($language)
    {
        /** @type \Judge\Contracts\Runner $runner */
        $runner = app(config("runners.{$language}", DefaultRunner::class));
        $compiler = config("compilers.{$language}");

        if (!$compiler) {
            throw new CompilerNotFoundException("No compiler for {$language}");
        }

        $runner->setCompiler(app($compiler));

        return $runner;
    }
}