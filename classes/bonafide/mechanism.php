<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Bona Fide is a flexible authentication system for the Kohana Framework.
 *
 * [!!] This module conflicts (intentionally) with the Auth module! Enabling both
 * at the same time will cause unexpected results!
 *
 * @package    Bona Fide
 * @category   Base
 * @author     Woody Gilk <woody.gilk@kohanaframework.org>
 * @copyright  (c) 2011 Woody Gilk
 * @license    MIT
 */
abstract class Bonafide_Mechanism {

	/**
	 * @param  string  unique hash prefix
	 */
	public $prefix;

	/**
	 * Applies configuration variables to the current mechanism.
	 *
	 * @param  array  configuration
	 */
	public function __construct(array $config = NULL)
	{
		if ($config)
		{
			foreach ($config as $name => $value)
			{
				if (property_exists($this, $name))
				{
					$this->$name = $value;
				}
			}
		}
	}

	/**
	 * Check a plaintext password against the hash of that password. 
	 *
	 * [!!] To increase security, use a unique salt and a random iteration
	 * count for every user!
	 *
	 * @param   string   plaintext password
	 * @param   string   hashed password
	 * @param   string   appended salt, should be unique per user
	 * @param   integer  number of iterations to run
	 * @return  boolean
	 */
	public function check($password, $hash, $salt = NULL, $iterations = NULL)
	{
		// Must always be an integer!
		$iterations = (int) $iterations;

		do
		{
			// Apply strengthening to the hashed password, for additional
			// details read: http://en.wikipedia.org/wiki/Key_strengthening
			$password = $this->hash($password, $salt);
		}
		while(--$iterations > 0);

		return ($hash === $password);
	}

	/**
	 * Get the hash of some text.
	 *
	 * @param   string  input text
	 * @param   string  appended salt
	 * @return  string
	 */
	abstract public function hash($input, $salt = NULL);

} // End Bonafide_Mechanism
