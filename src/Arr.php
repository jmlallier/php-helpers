<?php
namespace Jmlallier\PHPHelpers;

class Arr {

    /**
     * Return a mapped array of modified items.
     *
     * @param array $array
     * @param function $func
     * @return array $results
     */
    public static function map($array = array(), $func) {
       $results = array();

       foreach ($array as $key => $item) {
           $results[] = $func($item);
       }

       return $results;
    }

    /**
     * Return an array filtered by the passed
     *
     * @param array $array
     * @param function $func
     * @return array $results
     */
    public static function filter( $array = array(), $func ) {
       $results = array();

       foreach ($array as $key => $item) {
           if ($func($item)) {
               $results[$key] = $item;
           }
       }

       return $results;
    }

    /**
     * Performs an action on each item of an array.
     *
     * @param array $array
     * @param function $func
     * @return void
     */
    public static function each($array = array(), $func) {
       foreach ($array as $item) {
           $func($item);
       }
    }
}
