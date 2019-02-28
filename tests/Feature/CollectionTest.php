<?php
namespace Jmlallier\PHPHelpers\Test;

use Jmlallier\PHPHelpers\Collection;

class CollectionTest extends TestCase
{
	/**
	 * @test
	 */
	function a_collection_can_be_iterated_over()
	{
		$collection = Collection::make([1,2,3,4,5,6,7,8,9,10]);
		foreach ( $collection as $key => $item ) {
			$this->assertSame($key + 1, $collection->all()[$key]);
		}
	}

	/**
	 * @test
	 */
	function items_can_be_collected_and_added()
	{
		$arr = [1,2,3];
		$collection = Collection::make($arr);
		$this->assertSame($arr, $collection->all());

		$collection = new Collection($arr);
		$this->assertSame($arr, $collection->all());

		$collection = new Collection();
		$this->assertEmpty($collection);
		$collection = $collection->add($arr);
		$this->assertSame($arr, $collection->all());
	}

	/**
	 * @test
	 */
	function a_collection_can_be_chunked()
	{
		$chunks = Collection::make([1,2,3,4,5,6])
			->chunk(2);
		$this->assertCount(3, $chunks);
		$this->assertInstanceOf(Collection::class, $chunks);
		$this->assertInstanceOf(Collection::class, $chunks->take(1));

		$arrayedChunks = Collection::make([1,2,3,4,5,6])
			->chunkToArray(2);
		$this->assertCount(3, $arrayedChunks);
		$this->assertSame([[1,2], [3,4], [5,6]], $arrayedChunks);
	}

	/**
	 * @test
	 */
	function a_collection_can_be_flattened()
	{
		$collection = Collection::make([
			'one' => [
				[
					'title' => 'Test'
				],
				[
					'title' => 'Test 2'
				]
			],
			'two' => [
				[
					'title' => 'Test 3',
					[
						'sub' => 'data',
						'more' => [
							'evenMore' => 'data'
						]
					]
				]
			]
		]);
		$flattenedOnce = $collection->flatten(1)->all();
		$flattenedTwice = $collection->flatten(2)->all();
		$flattenedThrice = $collection->flatten(3)->all();
		$flattenedInfinitely = $collection->flatten()->all();

		$this->assertArrayNotHasKey('one', $flattenedOnce);
		$this->assertArrayHasKey('title', $flattenedOnce[0]);

		$this->assertNotSame($flattenedOnce[0], $flattenedTwice[0]);
		$this->assertArrayHasKey('sub', $flattenedTwice[3]);

		$this->assertNotSame($flattenedTwice[3], $flattenedThrice[3]);
		$this->assertArrayHasKey('evenMore', $flattenedThrice[4]);

		$this->assertNotSame($flattenedThrice[4], $flattenedInfinitely[4]);
		$this->assertSame('data', $flattenedInfinitely[4]);

		$empty = new Collection;

		$this->assertEmpty($empty->flatten()->all());
	}

	/**
	 * @test
	 */
	function a_collection_can_return_the_first_or_last_item()
	{
		$array = [1,2,3,4,5];
		$emptyArray = [];

		$this->assertSame(1, Collection::make($array)->first());
		$this->assertSame(5, Collection::make($array)->last());
		$this->assertEmpty(Collection::make($emptyArray)->first());
		$this->assertEmpty(Collection::make($emptyArray)->last());
	}
}
