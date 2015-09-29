<?php

namespace Judge\Problems;

use Illuminate\Database\Eloquent\Model;

/**
 * Judge\Problems\Problem
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|TestCase[] $testCases
 */
class Problem extends Model
{
    protected $table = 'problems';

    public function testCases()
    {
        return $this->hasMany(TestCase::class);
    }
}
