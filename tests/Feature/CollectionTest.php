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
}
