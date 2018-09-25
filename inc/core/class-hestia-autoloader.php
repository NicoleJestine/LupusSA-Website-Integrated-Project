<?php
/**
 * The file that defines autoload class
 *
 * A simple autoloader that loads class files recursively starting in the directory
 * where this class resides.  Additional options can be provided to control the naming
 * convention of the class files.
 *
 * @link        https://themeisle.com
 * @copyright   Copyright (c) 2017, Bogdan Preda
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 * @since       1.1.40
 * @package     hestia
 */

/**
 * The Autoloader class.
 *
 * @since      1.1.40
 * @package    hestia
 * @author     Themeisle <friends@themeisle.com>
 */
class Hestia_Autoloader {

	/**
	 * File extension as a string. Defaults to ".php".
	 *
	 * @since   1.1.40
	 * @access  protected
	 * @var     string $file_ext The file extension to look for.
	 */
	protected static $file_ext = '.php';

	/**
	 * The top level directory where recursion will begin. Defaults to the current
	 * directory.
	 *
	 * @since   1.1.40
	 * @access  protected
	 * @var     string $path_top The root directory.
	 */
	protected static $path_top = __DIR__;

	/**
	 * The plugin directory where recursion will begin. Defaults to empty ( No module will be loaded ).
	 *
	 * @since   1.1.40
	 * @access  protected
	 * @var     string $plugins_path The installation plugins directory.
	 */
	protected static $plugins_path = '';

	/**
	 * Holds an array of namespaces to filter in autoloading if set.
	 *
	 * @since   1.1.40
	 * @access  protected
	 * @var array $namespaces The namespace array, used if not empty on autoloading.
	 */
	protected static $namespaces = array();

	/**
	 * An array of files to exclude when looking to autoload.
	 *
	 * @since   1.1.40
	 * @access  protected
	 * @var     array $excluded_files The excluded files list.
	 */
	protected static $excluded_files = array();

	/**
	 * A placeholder to hold the file iterator so that directory traversal is only
	 * performed once.
	 *
	 * @since   1.1.40
	 * @access  protected
	 * @var     RecursiveIteratorIterator $file_iterator Holds an instance of the iterator class.
	 */
	protected static $file_iterator = null;

	/**
	 * Method to check in allowed namespaces.
	 *
	 * @since   1.1.40
	 * @access  protected
	 *
	 * @param   string $class_name the class name to check with the namespaces.
	 *
	 * @return bool
	 */
	protected static function check_namespaces( $class_name ) {
		$found = false;
		foreach ( static::$namespaces as $namespace ) {
			if ( substr( $class_name, 0, strlen( $namespace ) ) == $namespace ) {
				$found = true;
			}
		}

		return $found;
	}

	/**
	 * Autoload function for registration with spl_autoload_register
	 *
	 * Looks recursively through project directory and loads class files based on
	 * filename match.
	 *
	 * @since   1.1.40
	 * @access  public
	 *
	 * @param   string $class_name The class name requested.
	 *
	 * @return mixed
	 */
	public static function loader( $class_name ) {
		if ( ! empty( static::$namespaces ) ) {
			$found = static::check_namespaces( $class_name );
			if ( ! $found ) {
				return $found;
			}
		}

		$directory = new RecursiveDirectoryIterator( static::$path_top . DIRECTORY_SEPARATOR, RecursiveDirectoryIterator::SKIP_DOTS );
		require_once 'class-hestia-recursive-filter.php';

		if ( is_null( static::$file_iterator ) ) {
			$iterator              = new RecursiveIteratorIterator(
				new Hestia_Recursive_Filter(
					$directory,
					array(
						'Hestia_Autoloader',
						'filter_excluded_files',
					)
				)
			);
			$regex                 = new RegexIterator( $iterator, '/^.+\.php$/i', RecursiveRegexIterator::MATCH );
			static::$file_iterator = iterator_to_array( $regex, false );
		}

		$filename = 'class-' . str_replace( '_', '-', strtolower( $class_name ) ) . static::$file_ext;
		foreach ( static::$file_iterator as $file ) {
			if ( strtolower( $file->getFileName() ) === strtolower( $filename ) && is_readable( $file->getPathName() ) ) {
				require( $file->getPathName() );

				return true;
			}
		}
	}

	/**
	 * Sets the $file_ext property
	 *
	 * @since   1.1.40
	 * @access  public
	 *
	 * @param   string $file_ext The file extension used for class files.  Default is "php".
	 */
	public static function set_file_ext( $file_ext ) {
		static::$file_ext = $file_ext;
	}

	/**
	 * Sets the $path property
	 *
	 * @since   1.1.40
	 * @access  public
	 *
	 * @param   string $path The path representing the top level where recursion should
	 *                       begin. Defaults to the current directory.
	 */
	public static function set_path( $path ) {
		static::$path_top = $path;
	}

	/**
	 * Adds a new file to the exclusion list.
	 *
	 * @since   1.1.40
	 * @access  public
	 *
	 * @param   string $file_name The file name to exclude from autoload.
	 */
	public static function exclude_file( $file_name ) {
		static::$excluded_files[] = $file_name;
	}

	/**
	 * Define files the autoloader is going to recursively ignore.
	 *
	 * @param array $files_array the excluded files array.
	 */
	public static function define_excluded_files( $files_array ) {
		static::$excluded_files = array_merge( static::$excluded_files, $files_array );
	}

	/**
	 * Sets the namespaces used in autoloading if any.
	 *
	 * @since   1.1.40
	 * @access  public
	 *
	 * @param   array $namespaces The namespaces to use.
	 */
	public static function define_namespaces( $namespaces = array() ) {
		static::$namespaces = $namespaces;
	}

	/**
	 * Utility to filter out the excluded directories.
	 *
	 * @param \SplFileInfo                $file     The file info array.
	 * @param string                      $key      File key.
	 * @param \RecursiveDirectoryIterator $iterator The recursive directory iterator.
	 *
	 * @return bool
	 */
	public static function filter_excluded_files( \SplFileInfo $file, $key, \RecursiveDirectoryIterator $iterator ) {
		if ( ! in_array( $file->getFilename(), static::$excluded_files ) ) {
			return true;
		}
		return false;
	}
}
