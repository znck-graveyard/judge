<?php namespace Judge\Compilers;

    /**
     * This file belongs to judge.
     *
     * Author: Rahul Kadyan, <hi@znck.me>
     * Find license in root directory of this project.
     */
/**
 * Class Python
 *
 * @package Judge\Compilers
 */
class Python extends AbstractCompiler
{
    /**
     * @type string
     */
    protected $extension = 'py';

    /**
     * @param string $source
     * @param null   $output
     * @param null   $exit_code
     *
     * @return void
     */
    public function compile($source, &$output = null, &$exit_code = null)
    {
        $binary = 'sourcecode.py';
        $this->sandbox->put($binary, "#!/usr/bin/env python \n" . $source);
        chdir($this->sandbox->rootDirectory());
        exec('chmod +x sourcecode.py');
        $exit_code = 0;

        return $binary;
    }
}