<?php

use Codeception\Specify;
use Codeception\TestCase\Test;
use Jedrzej\Sortable\Criterion;

class CriterionTest extends Test
{
    use Specify;

    public function testParsing()
    {
        $this->specify("correct value is parsed", function () {
            $this->assertEquals('aBc', Criterion::make('aBc,asc')->getField());
            $this->assertEquals('cde', Criterion::make('cde,desc')->getField());
            $this->assertEquals('asc', Criterion::make('abc,asc')->getOrder());
            $this->assertEquals('desc', Criterion::make('cde,desc')->getOrder());
            $this->assertEquals('a.b', Criterion::make('a.b,asc')->getField());
            $this->assertEquals('a_b', Criterion::make('a_b,asc')->getField());
            $this->assertEquals('a-b', Criterion::make('a-b,asc')->getField());
            $this->assertEquals('a:b', Criterion::make('a:b,asc')->getField());

        });

        $this->specify("ascending order is used by default", function () {
            $this->assertEquals('abc', Criterion::make('abc')->getField());
            $this->assertEquals('asc', Criterion::make('abc')->getOrder());
        });

        $this->specify("incorrect value results in exception being thrown", function () {
            try {
                Criterion::make('a,b,c');
                $this->fail('Expected exception was not thrown');
            } catch (InvalidArgumentException $e) {
                //
            }

            try {
                Criterion::make('abc,dasc');
                $this->fail('Expected exception was not thrown');
            } catch (InvalidArgumentException $e) {
                //
            }

            try {
                Criterion::make(',asc');
                $this->fail('Expected exception was not thrown');
            } catch (InvalidArgumentException $e) {
                //
            }
        });

        $this->specify("field name is validated", function () {
            try {
                Criterion::make('a!b,asc');
                $this->fail('Expected exception was not thrown');
            } catch (InvalidArgumentException $e) {
                //
            }

            try {
                Criterion::make('a b,asc');
                $this->fail('Expected exception was not thrown');
            } catch (InvalidArgumentException $e) {
                //
            }

            try {
                Criterion::make('a#b,asc');
                $this->fail('Expected exception was not thrown');
            } catch (InvalidArgumentException $e) {
                //
            }
        });
    }
}
