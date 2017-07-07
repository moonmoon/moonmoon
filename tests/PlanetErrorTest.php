<?php

use PHPUnit\Framework\TestCase;

class PlanetErrorTest extends TestCase
{
    public function test_to_string()
    {
        $error = new PlanetError(1, 'foo');
        $this->assertEquals('notice: foo', $error->toString());
    }
}