<?php

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    function test_constant_time_compare()
    {
        $this->assertTrue(_hash_equals('abc', 'abc'));
        $this->assertFalse(_hash_equals('abc', 'ab'));
        $this->assertFalse(_hash_equals('ab', 'abc'));
        $this->assertFalse(_hash_equals('abcd', 'adbc'));
        $this->assertFalse(_hash_equals(0, 0));
    }
}
