<?php namespace Judge\Compilers;

    /**
     * This file belongs to judge.
     *
     * Author: Rahul Kadyan, <hi@znck.me>
     * Find license in root directory of this project.
     */
/**
 * Class AbstractCompiler
 *
 * @package Judge\Compilers
 */
abstract class AbstractCompiler
{
    /**
     * @type string output filename
     */
    protected $output;

    protected $command = 'compiler -o :output :input';

    /**
     * @param string $output
     *
     * @return void
     */
    public function setOutputFile($output)
    {
        $this->output = $output;
    }

    /**
     * @return string
     */
    public function getOutputFile()
    {
        return $this->output;
    }

    /**
     * @param string $source source code file
     * @param array  $output
     * @param int    $exit_code
     *
     * @return string compiled executable file
     */
    public function compile($source, &$output = null, &$exit_code = null)
    {
        $command = $this->getCommand();
        foreach (['input' => $source, 'output' => $this->output] as $key => $value) {
            $command = str_replace(':' . $key, $value, $command);
        }

        exec($command, $output, $exit_code);

        return $this->output;
    }

    protected function getCommand()
    {
        return $this->command;
    }
}
