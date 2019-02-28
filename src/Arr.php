<?php
namespace Jmlallier\PHPHelpers;

class Arr {

	/**
	 * Return a mapped array of modified items.
	 *
	 * @param array $array
	 * @param callable $func
	 * @return array $results
	 */
	public static function map(array $array = [], callable $func) {
	   $results = [];

	   foreach ($array as $key => $item) {
		   $results[] = $func($item, $key);
	   }

	   return $results;
	}

	/**
	 * Return an array filtered by the passed
	 *
	 * @param array $array
	 * @param callable $func
	 * @return array $results
	 */
	public static function filter(array $array = [], callable $func) {
	   $results = [];

	   foreach ($array as $key => $item) {
		   if ($func($item, $key)) {
			   $results[$key] = $item;
		   }
	   }

	   return $results;
	}

	/**
	 * Performs an action on each item of an array.
	 *
	 * @param array $array
	 * @param callable $func
	 * @return void
	 */
	public static function each(array $array = [], callable $func) {
	   foreach ($array as $key => $item) {
		   $func($item, $key);
	   }
	}

	/**
	 * Return the last element in an array.
	 *
	 * @param  array  $array
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function last(array $array, $default = null) {
		if ( ! empty($array) ) {
			return end($array);
		}
		return $default;
	}

	/**
	 * Return the first element in an array.
	 *
	 * @param  array  $array
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function first(array $array, $default = null) {
		if ( empty($array) ) {
			return $default;
		}
		foreach ($array as $item) {
			return $item;
		}
	}

	/**
	 * Get a subset of the items from the given array.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	public static function only(array $array, $keys) {
		return array_intersect_key($array, array_flip((array) $keys));
	}

	/**
	 * Get all of the given array except for a specified array of keys.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return array
	 */
	public static function except(array $array, $keys) {
		static::forget($array, $keys);
		return $array;
	}

	/**
	 * Remove one or many array items from a given array using "dot" notation.
	 *
	 * @param  array  $array
	 * @param  array|string  $keys
	 * @return void
	 */
	public static function forget(array &$array, $keys ) {
		$original = &$array;
		$keys     = (array) $keys;
		if ( count($keys) === 0 ) {
			return;
		}
		foreach ($keys as $key) {
			// if the exact key exists in the top-level, remove it
			if (array_key_exists($key, $array)) {
				unset($array[$key]);
				continue;
			}
			$parts = explode('.', $key);
			// clean up before each pass
			$array = &$original;
			while (count($parts) > 1) {
				$part = array_shift($parts);
				if (isset($array[$part]) && is_array($array[$part])) {
					$array = &$array[$part];
				} else {
					continue 2;
				}
			}
			unset($array[array_shift($parts)]);
		}
	}

	/**
	 * Flatten a multi-dimensional array into a single level.
	 *
	 * @param  array  $array
	 * @param  int  $depth
	 * @return array
	 */
	public static function flatten($array, $depth = INF)
	{
		$result = [];
		foreach ($array as $item) {
			$item = $item instanceof Collection ? $item->all() : $item;
			if (! is_array($item)) {
				$result[] = $item;
			} elseif ($depth === 1) {
				$result = array_merge($result, array_values($item));
			} else {
				$result = array_merge($result, static::flatten($item, $depth - 1));
			}
		}
		return $result;
	}

	/**
	 * If the given value is not an array and not null, wrap it in one.
	 *
	 * @param  mixed  $value
	 * @return array
	 */
	public static function wrap($value)
	{
		if (is_null($value)) {
			return [];
		}
		return is_array($value) ? $value : [$value];
	}
}
