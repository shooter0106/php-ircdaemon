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
	 * @param int $conID
	 */
	public static function createUser(string $nick, int $conID)
	{
		if (!isset(self::$_users[$nick])) {
			self::$_users[$conID] = new User($nick, $conID);
		} else {
			//TODO throw user exception
		}
	}

	/**
	 * Destruct User instance
	 *
	 * @param int $conID
	 */
	public static function destroyUser(int $conID)
	{
		unset(self::$_users[$conID]);
	}
}