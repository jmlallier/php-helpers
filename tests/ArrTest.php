<?php
namespace Jmlallier\PHPHelpers\Test;

use Jmlallier\PHPHelpers\Arr;
use Jmlallier\PHPHelpers\Str;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{

    /** @test */
    public function map_returns_an_array_of_modified_items()
    {
        $orig = array(
            'one' => array(
                'first_name' => 'John',
                'last_name' => 'Doe',
            ),
            'two' => array(
                'first_name' => 'Jane',
                'last_name' => 'Doe',
            ),
        );
        $lastNames = Arr::map($orig, function($item) {
            return $item['first_name'] . ' ' . $item['last_name'];
        });
        $this->assertEquals('John Doe', $lastNames[0]);
        $this->assertEquals('Jane Doe', $lastNames[1]);
    }

    /** @test */
    public function filter_returns_an_array_of_matching_items()
    {
        $orig = array(
            'one' => 100,
            'two' => 200,
            'three' => 300,
        );
        $filtered = Arr::filter($orig, function($item) {
            return $item > 100;
        });

        $this->assertCount(2, $filtered);
        $this->assertArrayHasKey('two', $filtered);
        $this->assertArrayHasKey('three', $filtered);
        $this->assertArrayNotHasKey('one', $filtered);
    }

    /** @test */
    public function each_performs_an_action_on_each_member_of_an_array()
    {
        $list = '';
        $array = array('one', 'two', 'three');
        Arr::each($array, function ($item) use (&$list) {
            $list = Str::append($item, $list, ' ');
        });
        $this->assertEquals('one two three', trim($list));
    }



}
