<?php

namespace Judge\Problems;

use Illuminate\Database\Eloquent\Model;

/**
 * Judge\Problems\TestCase
 *
 * @property-read Problem $problem
 */
class TestCase extends Model
{
    protected $table = 'test_cases';

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }
}
