<?php

use Judge\Factories\JudgeFactory;

class CompilerTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testC()
    {
        /** @type \Judge\Factories\JudgeFactory $factory */
        $factory = app(JudgeFactory::class);

        $factory->with('c');

        $exe = $factory->getCompiler()->compile('
#include <stdio.h>

int main() {
    int a;
    scanf("%d", &a);
    printf("%d", a + 1);
    return 0;
}');
        $factory->getRunner()->execute($exe, '1', $output, $error);

        $this->assertEquals('2', trim($output));
        $this->assertEquals('', trim($error));
    }
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCPP()
    {
        /** @type \Judge\Factories\JudgeFactory $factory */
        $factory = app(JudgeFactory::class);

        $factory->with('c++');

        $exe = $factory->getCompiler()->compile('
#include <iostream>
using namespace std;

int main() {
    int a;
    cin >> a;
    cout << a + 1 << endl;
    return 0;
}');
        $factory->getRunner()->execute($exe, '1', $output, $error);

        $this->assertEquals('2', trim($output));
        $this->assertEquals('', trim($error));
    }

    public function testPython() {
        /** @type \Judge\Factories\JudgeFactory $factory */
        $factory = app(JudgeFactory::class);

        $factory->with('python');

        $exe = $factory->getCompiler()->compile("a = int(raw_input()) \nprint a + 1\n");
        $factory->getRunner()->execute($exe, '1', $output, $error);

        $this->assertEquals('2', trim($output));
        $this->assertEquals('', trim($error));
    }
}
