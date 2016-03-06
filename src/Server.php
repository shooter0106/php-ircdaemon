<?php

namespace IRCPHP;

use IRCPHP\Entities\User;

class Server
{
	private static $_users = [];

	/**
	 * Create User instance
	 *
	 * @param string $nick
	 */
	public static function createUser(string $nick)
	{
		self::$_users[] = new User($nick);
	}
}