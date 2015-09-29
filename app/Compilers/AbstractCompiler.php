<?php namespace Judge\Compilers;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
use Judge\Contracts\Compiler;
use Judge\Contracts\Sandbox;
use Judge\Exceptions\CompilationFailedException;

/**
 * Class AbstractCompiler
 *
 * @package Judge\Compilers
 */
abstract class AbstractCompiler implements Compiler
{
    /**
     * @type string
     */
    protected $command = 'compiler -o :output :input';

    protected $extension = 'ext';
    /**
     * @type \Judge\Contracts\Sandbox
     */
    protected $sandbox;

    /**
     * AbstractCompiler constructor.
     *
     * @param \Judge\Contracts\Sandbox $sandbox
     */
    public function __construct(Sandbox $sandbox)
    {
        $this->sandbox = $sandbox;
    }


    /**
     * @param string $source source code file
     * @param array  $output
     * @param int    $exit_code
     *
     * @return string compiled executable file
     * @throws \Judge\Exceptions\CompilationFailedException
     */
    public function compile($source, &$output = null, &$exit_code = null)
    {
        $command = $this->getCommand();

        $this->sandbox->put('sourcecode.' . $this->extension, $source);
        $params = [
            'input'  => 'sourcecode.' . $this->extension,
            'output' => 'a.out',
        ];

        foreach ($params as $key => $value) {
            $command = str_replace(':' . $key, $value, $command);
        }

        chdir($this->sandbox->rootDirectory());
        exec($command, $output, $exit_code);

        if (0 !== $exit_code) {
            throw new CompilationFailedException(implode("\n", $output));
        }

        return $params['output'];
    }

    /**
     * @return string
     */
    protected function getCommand()
    {
        return $this->command;
    }
}
