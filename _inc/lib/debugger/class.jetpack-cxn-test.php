<?php
/**
 * Jetpack Connection Testing
 *
 * Various "unit tests" against the Jetpack connection.
 *
 * @author Brandon Kraft
 */

/**
 * "Unit Tests" for the Jetpack connection.
 *
 * @todo : Make a singleton?
 */
class Jetpack_Cxn_Test {

	/**
	 * The one true instance
	 *
	 * @var object $_instance
	 */
	private static $_instance = null;

	/**
	 * Tests to run on the Jetpack connection.
	 *
	 * @var array $tests
	 */
	private static $tests = array();

	/**
	 * Jetpack_Cxn_Test constructor.
	 */
	private function __construct() {
	}

	/**
	 * Adds a new test to the Jetpack Connection Testing suite.
	 *
	 * @param $callable callable Test to run.
	 *
	 * @return bool True if successfully added. False for a failure.
	 */
	private function add_test( $callable ) {
		if ( is_callable( $callable ) ) {
			$this->tests[] = $callable;
			return true;
		}

		return false;
	}

	private function run_tests() {
		$results = array();
		foreach ( $this->tests as $test ) {
			$result[ $test ] = call_user_func( $test );
		}
	}
}
