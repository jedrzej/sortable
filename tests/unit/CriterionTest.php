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
            $this->assertEquals('abc', Criterion::make('abc,asc')->getField());
            $this->assertEquals('cde', Criterion::make('cde,desc')->getField());
            $this->assertEquals('asc', Criterion::make('abc,asc')->getOrder());
            $this->assertEquals('desc', Criterion::make('cde,desc')->getOrder());
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
    }
}
