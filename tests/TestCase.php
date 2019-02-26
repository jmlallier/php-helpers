<?php
namespace Jmlallier\PHPHelpers\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	/**
	 * Dump the contents of a variable into the readable stream.
	 *
	 * @param mixed $var
	 * @param string $message
	 * @return void
	 */
	function dump( $var, $message = 'Var' ) {
		fwrite(STDERR, $message . "\r\n" . print_r($var, TRUE));
	}
}
