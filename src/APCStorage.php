<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Nette\Caching\Storages;

use Nette;
use Nette\Caching\Cache;


/**
 * APC storage.
 */
class APCStorage extends Nette\Object implements Nette\Caching\IStorage
{
	/**
	 * Checks if MongoDB extension is available.
	 * @return bool
	 */
	public static function isAvailable()
	{
		return extension_loaded('apc');
	}


	public function __construct()
	{
		if (!static::isAvailable()) {
			throw new Nette\NotSupportedException("PHP extension 'apc' is not loaded.");
		}
	}

	/**
	 * Read from cache.
	 * @param  string key
	 * @return mixed|NULL
	 */
	public function read($key)
	{
		$data = apc_fetch($key);

		// TODO: expire & slide. ...
		
		return $data['data'];
	}


	/**
	 * Prevents item reading and writing. Lock is released by write() or remove().
	 * @param  string key
	 * @return void
	 */
	public function lock($key)
	{
	}


	/**
	 * Writes item into the cache.
	 * @param  string key
	 * @param  mixed  data
	 * @param  array  dependencies
	 * @return void
	 */
	public function write($key, $data, array $dependencies)
	{
		$expire = isset($dependencies[Cache::EXPIRATION]) ? $dependencies[Cache::EXPIRATION] + time() : NULL;
		$slide = isset($dependencies[Cache::SLIDING]) ? $dependencies[Cache::EXPIRATION] : NULL;

		$data = array(
			'data' => $data,
			'expire' => $expire,
			'slide' => $slide
		);

		apc_store($key, $data);

		// TODO: tags
	}


	/**
	 * Removes item from the cache.
	 * @param  string key
	 * @return void
	 */
	public function remove($key)
	{
		
	}


	/**
	 * Removes items from the cache by conditions & garbage collector.
	 * @param  array  conditions
	 * @return void
	 */
	public function clean(array $conditions)
	{
		
	}

}
