<?php
namespace Jmlallier\PHPHelpers\Test;

use Jmlallier\PHPHelpers\Str;

class StrTest extends TestCase
{

    /** @test */
    public function it_can_determines_if_a_string_contains_a_search_term()
    {
        $subject = "Foo bar foo baz fizzbuzz";
        $this->assertTrue(Str::contains('baz', $subject));
        $this->assertFalse(Str::contains('biz', $subject));
    }

    /** @test */
    public function it_can_determines_if_any_of_an_array_of_strings_are_in_a_string()
    {
        $searches = [
            'foo',
            'bar'
        ];
        $subject = "Fiz bar fuzz biz";
        $this->assertTrue(Str::contains($searches, $subject));
        $subject = "Fiz baz fuzz biz";
        $this->assertFalse(Str::contains($searches, $subject));
    }

    /** @test */
    public function it_can_determine_if_a_string_is_equal_to_another_string()
    {
        $this->assertTrue(Str::is('foo', 'foo'));
        $this->assertFalse(Str::is('fiz', 'foo'));
    }

    /** @test */
    public function it_is_case_sensitive()
    {
        $this->assertFalse(Str::is('Foo', 'foo'));
    }

    /** @test */
    public function it_can_determine_if_any_of_an_array_of_strings_is_equal_to_another_string()
    {
        $strings = [
            'foo',
            'bar'
        ];
        $this->assertTrue(Str::is($strings, 'bar'));
        $this->assertFalse(Str::is($strings, 'fizz'));
    }

    /** @test */
    public function it_returns_the_length_of_a_string()
    {
        $this->assertEquals(4, Str::length('four'));
    }

    /** @test */
    public function it_replaces_a_string_within_another_string()
    {
        $original = 'Two plus two equals three';
        $shouldReturn = 'Two plus two equals four';
        $this->assertEquals($shouldReturn, Str::replace('three', 'four', $original));
    }

    /** @test */
    public function it_returns_the_original_string_if_the_search_term_is_not_found()
    {
        $original = 'Two plus two equals four';
        $this->assertEquals($original, Str::replace('three', 'four', $original));
    }

    /** @test */
    public function it_determines_whether_a_string_begins_or_ends_with_another_string()
    {
        $subject = "I end with a bow.";
        $this->assertTrue(Str::endsWith('.', $subject));
        $this->assertFalse(Str::endsWith('bow', $subject));
        $this->assertTrue(Str::beginsWith('I', $subject));
        $this->assertFalse(Str::beginsWith('end', $subject));
    }

    /** @test */
    public function it_forces_string_search_for_ending_search()
    {
        $subject = "We are #1";
        $this->assertTrue(Str::endsWith(1, $subject));
        $this->assertTrue(Str::endsWith('#1', $subject));
    }


    /** @test */
    public function it_appends_a_string_to_another_string()
    {
        $original = "I'm a little";
        $append = "teapot!";
        $this->assertEquals("I'm a little teapot!", Str::append($append, $original));
        $this->assertEquals("I'm a little", Str::append('', $original));
    }

    /** @test */
    public function it_prepends_a_string_to_another_string()
    {
        $original = "let the dogs out?";
        $prepend = "Who";
        $this->assertEquals("Who let the dogs out?", Str::prepend($prepend, $original));
        $this->assertEquals("let the dogs out?", Str::prepend('', $original));
    }

    /** @test */
    public function it_combines_two_strings()
    {
        $string_one = "anchovy";
        $string_two = "pizza";
        $strings = [$string_one, $string_two];
        $this->assertEquals("anchovy pizza", Str::combine($string_one, $string_two));
        $this->assertEquals("anchovy pizza", Str::combine($strings));
        $this->assertEquals("anchovy-pizza", Str::combine($strings, '-'));
    }

    /** @test */
    public function it_combines_many_strings()
    {
        $strings = [
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
        ];
        $this->assertEquals('one_two_three_four_five_six_seven', Str::combine($strings, '_'));
    }



}
