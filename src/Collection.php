<?php

namespace Jmlallier\PHPHelpers;

use \Countable;
use \IteratorAggregate;
use Jmlallier\PHPHelpers\Arr;

class Collection implements IteratorAggregate, Countable
{

	protected $items;

	function __construct($items = [])
	{
		$this->items = $this->getArrayableItems($items);
	}

	public function getIterator()
	{
		foreach ($this->items as $item) {
			yield $item;
		}
	}

	public static function make($items = [])
	{
		return new static($items);
	}

	public function add($item)
	{
		return $this->merge($item);
	}

	/**
	 * Run a map over each of the items.
	 *
	 * @param  callable  $callback
	 * @return static
	 */
	public function map(callable $callback)
	{
		$keys = array_keys($this->items);
		$items = array_map($callback, $this->items, $keys);
		return new static(array_combine($keys, $items));
	}

	public function filter($callback)
	{
		return new static(array_filter($this->items, $callback));
	}

	public function flatten($depth = INF) {
		return new static(Arr::flatten($this->items, $depth));
	}

	public function all()
	{
		return $this->items;
	}

	public function average($key = null)
	{
		$items = $this->items;
		if ($key && array_key_exists($key, $this->items)) {
			$items = static::make($this->items)->only($key)->all()[$key];
		}
		return $items ? array_sum($items) / count($items) : 0;
	}

	public function avg($key = null)
	{
		return $this->average($key);
	}

	public function only($keys)
	{
		$items = array_intersect_key($this->items, array_flip((array)$keys));
		return new static($items);
	}

	public function except($keys)
	{
		$items = array_diff($this->items, $this->only($keys)->toArray());
		return new static($items);
	}

	public function dump()
	{
		return print_r($this->items, true);
	}

	/**
	 * Get the items in the collection that are not present in the given items.
	 *
	 * @param  mixed  $items
	 * @return static
	 */
	public function diff($items)
	{
		return new static(array_diff($this->items, $this->getArrayableItems($items)));
	}

	/**
	 * Get the items in the collection whose keys and values are not present in the given items.
	 *
	 * @param  mixed  $items
	 * @return static
	 */
	public function diffAssoc($items)
	{
		return new static(array_diff_assoc($this->items, $this->getArrayableItems($items)));
	}

	/**
	 * Execute a callback over each item.
	 *
	 * @param  callable  $callback
	 * @return $this
	 */
	public function each(callable $callback)
	{
		foreach ($this->items as $key => $item) {
			if ($callback($item, $key) === false) {
				break;
			}
		}
		return $this;
	}

	/**
	 * Determine if all items in the collection pass the given test.
	 *
	 * @param  string|callable  $key
	 * @param  mixed  $operator
	 * @param  mixed  $value
	 * @return bool
	 */
	public function every($key, $operator = null, $value = null)
	{
		if (func_num_args() === 1) {
			$callback = $this->valueRetriever($key);
			foreach ($this->items as $k => $v) {
				if (!$callback($v, $k)) {
					return false;
				}
			}
			return true;
		}
		return $this->every($this->operatorForWhere(...func_get_args()));
	}

	/**
	 * Determine if the collection is empty or not.
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->items);
	}

	/**
	 * Determine if the collection is not empty.
	 *
	 * @return bool
	 */
	public function isNotEmpty()
	{
		return !$this->isEmpty();
	}

    /**
     * Sort through each item with a callback.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function sort(callable $callback = null)
    {
        $items = $this->items;
        $callback
            ? uasort($items, $callback)
            : asort($items);
        return new static($items);
    }
    /**
     * Sort the collection using the given callback.
     *
     * @param  callable|string  $callback
     * @param  int  $options
     * @param  bool  $descending
     * @return static
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
    {
        $results = [];
        $callback = $this->valueRetriever($callback);
        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }
        $descending ? arsort($results, $options)
            : asort($results, $options);
        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }
        return new static($results);
    }
    /**
     * Sort the collection in descending order using the given callback.
     *
     * @param  callable|string  $callback
     * @param  int  $options
     * @return static
     */
    public function sortByDesc($callback, $options = SORT_REGULAR)
    {
        return $this->sortBy($callback, $options, true);
    }
    /**
     * Sort the collection keys.
     *
     * @param  int  $options
     * @param  bool  $descending
     * @return static
     */
    public function sortKeys($options = SORT_REGULAR, $descending = false)
    {
        $items = $this->items;
        $descending ? krsort($items, $options) : ksort($items, $options);
        return new static($items);
    }
    /**
     * Sort the collection keys in descending order.
     *
     * @param  int $options
     * @return static
     */
    public function sortKeysDesc($options = SORT_REGULAR)
    {
        return $this->sortKeys($options, true);
    }
    /**
     * Splice a portion of the underlying collection array.
     *
     * @param  int  $offset
     * @param  int|null  $length
     * @param  mixed  $replacement
     * @return static
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        if (func_num_args() === 1) {
            return new static(array_splice($this->items, $offset));
        }
        return new static(array_splice($this->items, $offset, $length, $replacement));
    }
    /**
     * Get the sum of the given values.
     *
     * @param  callable|string|null  $callback
     * @return mixed
     */
    public function sum($callback = null)
    {
        if (is_null($callback)) {
            return array_sum($this->items);
        }
        $callback = $this->valueRetriever($callback);
        return $this->reduce(function ($result, $item) use ($callback) {
            return $result + $callback($item);
        }, 0);
    }
    /**
     * Take the first or last {$limit} items.
     *
     * @param  int  $limit
     * @return static
     */
    public function take($limit)
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }
        return $this->slice(0, $limit);
    }

    /**
     * Slice the underlying collection array.
     *
     * @param  int  $offset
     * @param  int  $length
     * @return static
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    /**
     * Pass the collection to the given callback and then return it.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function tap(callable $callback)
    {
        $callback(new static($this->items));
        return $this;
	}

	public function tapInto(callable $callback) {
		return new static($this->getArrayableItems($callback($this->all())));
	}

	public function newFrom(callable $callback, $with = []){
		return $this->tapInto($callback)->merge($this->getArrayableItems($with));
	}

	/**
	 * Pass the collection through a callback.
	 *
	 * Some callbacks return an arrayable response with values that you want,
	 * but with the values you already have.
	 *
	 * @param callable $callback
	 * @return Mediavine\Support\Collection
	 */
	public function passThrough(callable $callback) {
		return $this->newFrom($callback, $this);
	}

	public function get($key) {
		if ( \array_key_exists($key, $this->items)){
			return $this->getArrayableItems($this->items[$key]);
		}
	}

    /**
     * Transform each item in the collection using a callback.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function transform(callable $callback)
    {
        $this->items = $this->map($callback)->all();
        return $this;
    }

	/**
	 * Return only unique items from the collection array.
	 *
	 * @param  string|callable|null  $key
	 * @param  bool  $strict
	 * @return static
	 */
	public function unique($key = null, $strict = false)
	{
		$callback = $this->valueRetriever($key);
		$exists = [];
		return $this->reject(function ($item, $key) use ($callback, $strict, &$exists) {
			if (in_array($id = $callback($item, $key), $exists, $strict)) {
				return true;
			}
			$exists[] = $id;
		});
	}

	/**
	 * Return only unique items from the collection array using strict comparison.
	 *
	 * @param  string|callable|null  $key
	 * @return static
	 */
	public function uniqueStrict($key = null)
	{
		return $this->unique($key, true);
	}

    /**
     * Chunk the underlying collection array.
     *
     * @param  int  $size
     * @return static
     */
    public function chunk($size)
    {
        if ($size <= 0) {
            return new static;
        }
        $chunks = [];
        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = new static($chunk);
        }
        return new static($chunks);
    }

    public function chunkToArray($size) {
        if ( $size <= 0) {
            return [];
        }
        return array_chunk($this->items, $size, false);
    }

	/**
	 * Reset the keys on the underlying array.
	 *
	 * @return static
	 */
	public function values()
	{
		return new static(array_values($this->items));
	}

	/**
	 * Merge the collection with the given items.
	 *
	 * @param  mixed  $items
	 * @return static
	 */
	public function merge($items)
	{
		return new static(array_merge($this->items, $this->getArrayableItems($items)));
	}

	/**
	 * Create a collection by using this collection for keys and another for its values.
	 *
	 * @param  mixed  $values
	 * @return static
	 */
	public function combine($values)
	{
		return new static(array_combine($this->all(), $this->getArrayableItems($values)));
	}

	public function count() {
		return count($this->items);
	}

	/**
	 * Pass the collection to the given callback and return the result.
	 *
	 * @param  callable $callback
	 * @return mixed
	 */
	public function pipe(callable $callback)
	{
		return $callback($this);
	}

	/**
	 * Get and remove the last item from the collection.
	 *
	 * @return mixed
	 */
	public function pop()
	{
		return array_pop($this->items);
	}

	/**
	 * Reduce the collection to a single value.
	 *
	 * @param  callable  $callback
	 * @param  mixed  $initial
	 * @return mixed
	 */
	public function reduce(callable $callback, $initial = null)
	{
		return array_reduce($this->items, $callback, $initial);
	}

	/**
	 * Create a collection of all elements that do not pass a given truth test.
	 *
	 * @param  callable|mixed  $callback
	 * @return static
	 */
	public function reject($callback = true)
	{
		$useAsCallable = $this->useAsCallable($callback);
		return $this->filter(function ($value, $key) use ($callback, $useAsCallable) {
			return $useAsCallable
				? !$callback($value, $key)
				: $value != $callback;
		});
	}

	/**
	 * Reverse items order.
	 *
	 * @return static
	 */
	public function reverse()
	{
		return new static(array_reverse($this->items, true));
	}

	/**
	 * Search the collection for a given value and return the corresponding key if successful.
	 *
	 * @param  mixed  $value
	 * @param  bool  $strict
	 * @return mixed
	 */
	public function search($value, $strict = false)
	{
		if (!$this->useAsCallable($value)) {
			return array_search($value, $this->items, $strict);
		}
		foreach ($this->items as $key => $item) {
			if (call_user_func($value, $item, $key)) {
				return $key;
			}
		}
		return false;
	}

	/**
	 * Get and remove the first item from the collection.
	 *
	 * @return mixed
	 */
	public function shift()
	{
		return array_shift($this->items);
	}

	protected function getArrayableItems($items)
	{
		if (is_array($items)) {
			return $items;
		} elseif ($items instanceof self) {
			return $items->all();
		}
		return (array)$items;
	}

	/**
	 * Determine if the given value is callable, but not a string.
	 *
	 * @param  mixed  $value
	 * @return bool
	 */
	protected function useAsCallable($value)
	{
		return !is_string($value) && is_callable($value);
	}

	/**
	 * Get a value retrieving callback.
	 *
	 * @param  string  $value
	 * @return callable
	 */
	protected function valueRetriever($value)
	{
		if ($this->useAsCallable($value)) {
			return $value;
		}
		return function ($item) use ($value) {
			return data_get($item, $value);
		};
	}

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }
            return $value;
        }, $this->items);
    }
    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

	public function toArray()
	{
		return $this->items;
	}

    /**
     * Get a base Support collection instance from this collection.
     *
     * @return \Mediavine\Support\Collection
     */
    public function toBase()
    {
        return new self($this);
	}

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }
    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }
    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }
    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }
    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

}
