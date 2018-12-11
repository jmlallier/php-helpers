<?php
namespace Jmlallier\PHPHelpers;

class Str {

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string|array  $searches
     * @param  string  $subject
     * @return bool
     */
    public static function contains( $searches, string $subject ) {
        foreach ( (array) $searches as $search ) {
            if ( '' !== $search && false !== strpos( $subject, $search ) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string|array  $possibilities
     * @param  string  $value
     * @return bool
     */
    public static function is( $possibilities, string $value ) {
        foreach ( (array) $possibilities as $possibility ) {
            if ( $possibility === $value ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the length of the given string.
     *
     * @param  string  $value
     * @param  string  $encoding
     * @return int
     */
    public static function length( $value, $encoding = null ) {
        $encoding = $encoding ? $encoding : mb_internal_encoding();
        return mb_strlen( $value, $encoding );
    }

    /**
     * Replace each occurrence of a given value in the string.
     *
     * @param  string|int  $search
     * @param  string|int  $replace
     * @param  string  $subject
     * @return string
     */
    public static function replace( $search, $replace, string $subject ) {
        if ( empty( $search ) ) {
            return $subject;
        }
        $search = (string) $search;
        $replace = (string) $replace;
        $position = strpos( $subject, $search );
        if ( false !== $position ) {
            $subject = substr_replace( $subject, $replace, $position, strlen( $search ) );
            return static::replace( $search, $replace, $subject );
        }
        return $subject;
    }

    /**
     * Determines whether string `$subject` ends with string `$search`.
     *
     * @param string|int $search
     * @param string $needle
     * @return boolean
     */
    public static function endsWith($search, string $subject)
    {
        $search = (string) $search;
        $length = strlen($search);
        if ($length == 0) {
            return true;
        }
        return substr($subject, -$length) === $search;
    }

    /**
     * Appends a string to the end of a string.
     *
     * @param string $append
     * @param string $subject
     * @param string $appendWith
     * @return string the concatenated string
     */
    public static function append( string $append, string $subject = '', string $appendWith = ' ' ) {
        if ( empty( $append ) ) {
            return $subject;
        }
        return $subject . $appendWith . $append;
    }

    /**
     * Appends a string to the end of a string.
     *
     * @param string $prepend
     * @param string $subject
     * @param string $appendWith
     * @return string the concatenated string
     */
    public static function prepend( $prepend, $subject, $prependWith = ' ' ) {
        if ( empty( $prepend ) || empty( $subject ) ) {
            return $subject;
        }
        return $prepend . $prependWith . $subject;
    }
}
