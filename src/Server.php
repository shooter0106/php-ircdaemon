<?php

namespace IRCPHP;

use IRCPHP\Entities\User;
use IRCPHP\Entities\Channel;
use Workerman\Worker;

class Server
{
	private static $_users = [];
	private static $_channels = [];

	/**
	 * Create User instance
	 *
	 * @param array $params
	 */
	public static function createUser(array $params, $connection)
	{
		if (!isset(self::$_users[$connection->id])) {
			self::$_users[$connection->id] = new User($params, $connection);
		} else {
			//TODO throw user exception
		}
	}

	/**
	 * Destruct User instance
	 */
	public static function destroyUser($connection)
	{
		unset(self::$_users[$connection->id]);
	}

	/**
	 * Join to channel
	 *
	 * @param string $channelName
	 * @param $connection
	 */
	public static function joinChannel(string $channelName, $connection)
	{
		if (!isset(self::$_channels[$channelName])) {
			self::createChannel($channelName, $connection);
			$user = self::getUser($connection);
			self::$_channels[$channelName]->addUser($user);
			$connection->send(":{$user->getNick()}!~{$user->getNick()}@{$user->getHost()} JOIN {$channelName} * {$user->getRealname()}\n\r");
		} else {}
	}

	/**
	 * Creating channel
	 *
	 * @param string $channelName
	 * @param $connection
	 */
	public static function createChannel(string $channelName, $connection)
	{
		self::$_channels[$channelName] = new Channel($channelName);
		print "Channel {$channelName} created\n";
	}

	/**
	 * Get User information
	 *
	 * @param $connection
	 * @return User
	 */
	public static function getUser($connection):User
	{
		return self::$_users[$connection->id];
	}

	/**
	 * Get channel modes
	 *
	 * @param string $channelName
	 * @param $connection
	 */
	public static function getChannelModes(string $channelName, $connection)
	{
		$channel = self::$_channels[$channelName];
		$connection->send("324 {$channelName} {$channel->getModes()}\n\r");
	}

	/**
	 * Get users on channel
	 *
	 * @param string $channelName
	 * @param $connection
	 */
	public static function getChannelUsers(string $channelName, $connection)
	{
		$users = self::$_channels[$channelName]->getUsers();
		foreach ($users as $user) {
			$connection->send("{$channelName} {$user->getNick()} {$user->getHost()} localhost.localdomain {$user->getNick()} H :0 realname\n\r");//TODO Fix
		}
		$user = self::getUser($connection);
		$connection->send("{$user->getNick()} {$channelName} :End of /WHO list.\n\r");//TODO Debug
	}

	public static function sendMessage(array $params, $connection)
	{
		$user = self::getUser($connection);
		$connection->send(":{$user->getNick()}!~{$user->getHost()} PRIVMSG {$params['receiver']} {$params['message']}\n\r");
	}

	public static function getChannelsList($connection)//TODO need more fixes
	{
		print "Requested channel list.\n";
		$user = self::getUser($connection);
		$connection->send("Channel :Users  Name\n\r");
		foreach (self::$_channels as $channel) {
			$connection->send("{$channel->getName()} {$channel->getUsersCount()} :[{$channel->getModes()}] {$channel->getTopic()}\n\r");
		}
		$connection->send(":End of /LIST\n\r");
	}
}