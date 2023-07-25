<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ExpressionTest extends TestCase
{
    /** @test */
    public function it_finds_a_string()
    {
        $regx =  Expression::make()->find('www');

        $this->assertTrue($regx->test('www'));

        $regx =  Expression::make()->then('www');

        $this->assertTrue($regx->test('www'));
    }

    /** @test */
    public function it_checks_for_anything()
    {
        $regx =  Expression::make()->anything();

        $this->assertTrue($regx->test('foo'));
    }

    /** @test */
    public function it_maybe_has_a_value()
    {
        $regx = Expression::make()->maybe('http');

        $this->assertTrue($regx->test(''));
        $this->assertTrue($regx->test('http'));

    }

    /** @test */
    public function it_can_chain_methods_call()
    {
        $regx =  Expression::make()->find('http')->maybe('s')->then(':www.warso');

        $this->assertTrue($regx->test('https:www.warso.com'));
        $this->assertTrue($regx->test('http:www.warso.com'));

        $this->assertFalse($regx->test('httpXwww.warso'));

    }

    // /** @test */
    // public function it_can_exclude_values() //need to implement anythingBut
    // {
    //     $regx = Expression::make()
    //         ->find('foo')
    //         ->anythingBut('bar')
    //         ->then('biz');

    //     $this->assertTrue($regx->test('foobazbiz'));
    //     $this->assertFalse($regx->test('foo bar biz'));
    // }
}
