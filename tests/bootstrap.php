<?php

class ZephyrTestsBootstrap {
	/**
	 * The bootstrap instance.
	 *
	 * @var ZephyrTestsBootstrap
	 */
	protected static $instance = null;

	/**
	 * Testing directory.
	 *
	 * @var string
	 */
	public $tests_dir;

	/**
	 * Library directory.
	 *
	 * @var string
	 */
	public $library_directory;

	/**
	 * Setup the unit testing environment
	 */
	private function __construct() {
		ini_set( 'display_errors','on' );
		error_reporting( E_ALL );

		$this->tests_dir = __DIR__;
		$this->library_directory = dirname( $this->tests_dir );

		define( 'MY_APP_TEST_DIR', $this->tests_dir );

		if ( ! defined( 'SCRIPT_DEBUG' ) ) {
			define( 'SCRIPT_DEBUG', false );
		}

		$autoload = $this->library_directory . '/vendor/autoload.php';

		if ( ! file_exists( $autoload ) ) {
			throw new Exception(
				'Tests could not be initialized in ' . static::class . '. ' .
				'Did you miss to run `composer install` in `' . $this->library_directory . '`?'
			);
		}

		// load dependencies
		require_once $autoload;
	}
	/**
	 * Get the single tests boostrap instance
	 *
	 * @return ZephyrTestsBootstrap
	 */
	public static function instance() {
		if ( static::$instance === null ) {
			static::$instance = new self();
		}

		return static::$instance;
	}
}

ZephyrTestsBootstrap::instance();
