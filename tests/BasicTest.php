<?php

namespace Pipes\Test;

use Pipes\Test\Tools\TestIteratorAggregate;

class BasicTest extends BaseTestCase
{
    public function testWrapper()
    {
        $array = $this->numerics();
        $this->assertEquals(p($array)->toArray(), $array);

        list($a, $b, $c, $d) = p($array)->toArray();
        $this->assertEquals($b, 2);

        $result = [];
        foreach (p($array)->toArray() as $value) {
            $result[] = $value;
        }
        $this->assertEquals($array, $result);
        $this->assertEquals(p($array)->toArray(), $result);
    }

    public function testForeach()
    {
        $array = $this->numerics();
        $obj = p($array);
        foreach ($obj as $v) {
            $this->assertSame(array_shift($array), $v);
        }
    }

    public function testDecorate()
    {
        $array = $this->numerics();
        $pipe = p($array);
        $obj = p($pipe);
        foreach ($obj as $v) {
            $this->assertSame(array_shift($array), $v);
        }
    }

    public function testChaining()
    {
        $array = $this->numerics();
        $pipe = p($array)->filter(function () {
            return false;
        });
        foreach ($pipe as $v) {
            $this->fail();
        }

        $array = $this->numerics();
        $pipe = p($array)
            ->filter(function () {
                return false;
            })
            ->filter(function () {
            return false;
        })
        ;
        foreach ($pipe as $v) {
            $this->fail();
        }

        $this->assertEquals(get_class(p($array)), get_class($pipe));
    }

    public function testToIterator()
    {
        $array = $this->associative();
        $this->assertInstanceOf(\Iterator::class, p($array)->toIterator());
    }

    public function testUnwrap()
    {
        $expected = $this->associative();
        $obj = p($expected);
        $this->assertSame($expected, $obj->unwrap());

        $expected = new TestIteratorAggregate($this->associative());
        $obj = p($expected);
        $this->assertSame($expected, $obj->unwrap());
    }
}
