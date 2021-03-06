<?php namespace Judge\Compilers;

use Judge\Contracts\Compiler;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class C extends AbstractCompiler implements Compiler
{
    protected $command = 'gcc -o :output :input';

    protected $extension = 'c';
}