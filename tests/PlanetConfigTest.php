<?php

use PHPUnit\Framework\TestCase;

class PlanetConfigTest extends TestCase
{
    public function test_default_configuration_values()
    {
        $conf = new PlanetConfig();
        $this->assertEquals('http://www.example.com/', $conf->getUrl());
    }

    public function test_merge_user_configuration_with_default_one()
    {
        $conf = new PlanetConfig(['url' => 'http://foobar.tld']);
        $this->assertEquals('http://foobar.tld', $conf->getUrl());
    }

    public function test_generic_getter()
    {
        $conf = new PlanetConfig(['foo' => 'bar']);
        $this->assertEquals('bar', $conf->foo);
    }

    public function test_generic_setter()
    {
        $conf = new PlanetConfig();
        $conf->foo = 'bar';
        $this->assertEquals('bar', $conf->foo);
    }

    public function test_normalize_key_name_on_merge()
    {
        $conf = new PlanetConfig(['FOO' => 'bar']);
        $this->assertEquals('bar', $conf->foo);
    }

    public function test_normalize_key_name_on_generic_getter()
    {
        $conf = new PlanetConfig(['foo' => 'bar']);
        $this->assertEquals('bar', $conf->FOO);
    }

    public function test_normalize_key_name_on_generic_setter()
    {
        $conf = new PlanetConfig();
        $conf->FOO = 'bar';
        $this->assertEquals('bar', $conf->foo);
    }

    public function test_to_array()
    {
        $conf = new PlanetConfig(['foo' => 'bar']);
        $this->assertEquals('bar', $conf->toArray()['foo']);
        $this->assertEquals('http://www.example.com/', $conf->toArray()['url']);
    }

    public function test_constructor_without_default_config()
    {
        $conf = new PlanetConfig(['foo' => 'bar'], false);
        $this->assertEquals('bar', $conf->foo);
        $this->assertEquals(1, sizeof($conf->toArray()));
    }

    public function test_to_yaml()
    {
        $conf = new PlanetConfig([], false);
        $this->assertEquals("---\n", $conf->toYaml());

        $conf = new PlanetConfig(['foo' => 'bar'], false);
        $this->assertEquals("---\nfoo: bar\n", $conf->toYaml());
    }
}
