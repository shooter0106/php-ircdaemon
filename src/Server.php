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
		if (!isset(self::$_users[$nick])) {
			self::$_users[$nick] = new User($nick);
		} else {
			//TODO throw user exception
		}
	}

	public static function destroyUser(string $nick)
	{}
}