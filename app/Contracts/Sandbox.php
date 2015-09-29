<?php namespace Judge\Contracts;

    /**
     * This file belongs to judge.
     *
     * Author: Rahul Kadyan, <hi@znck.me>
     * Find license in root directory of this project.
     */
/**
 * Interface Sandbox
 *
 * @package Judge\Contracts
 */
interface Sandbox
{
    public function refresh();

    public function destroy();

    public function absolutePath($filename);

    public function newFile($content = null, $extension = 'txt');

    public function put($filename, $content);

    public function get($filename);

    public function rootDirectory();
}