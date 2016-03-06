<?php

namespace IRCPHP;

use IRCPHP\Entities\User;
use IRCPHP\Entities\Channel;

class Server
{
	private static $_users = [];
	private static $_channels = [];

	/**
	 * Create User instance
	 *
	 * @param string $nick
	 * @param int $conID
	 */
	public static function createUser(string $nick, $connection)
	{
		if (!isset(self::$_users[$nick])) {
			self::$_users[$connection->id] = new User($nick, $connection->id);
		} else {
			//TODO throw user exception
		}
	}

	/**
	 * Destruct User instance
	 * @param int $conID
	 */
	public static function destroyUser($connection)
	{
		unset(self::$_users[$connection->id]);
	}

	public static function joinChannel(string $channelName, $connection)
	{
		if (!isset(self::$_channels[$channelName])) {
			self::createChannel($channelName, $connection);
		} else {}
	}

	public static function createChannel(string $channelName, $connection)
	{
		self::$_channels[$channelName] = new Channel($channelName);
		print "Channel {$channelName} created\n";
		$connection->send(":shooter!~shooter@127.0.0.1 JOIN #test * :realname\n\r");
	}
}