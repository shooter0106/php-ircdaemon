<?php

namespace IRCPHP;

use IRCPHP\Entities\User;
use IRCPHP\Entities\Channel;
use Workerman\Connection\TcpConnection;
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
	public static function createUser(array $params, TcpConnection $connection)
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
	public static function destroyUser(TcpConnection $connection)
	{
		unset(self::$_users[$connection->id]);
	}

	/**
	 * Join to channel
	 *
	 * @param string $channelName
	 * @param $connection
	 */
	public static function joinChannel(string $channelName, TcpConnection $connection)
	{
		if (!isset(self::$_channels[$channelName])) {
			$channel = self::createChannel($channelName, $connection);
			$user = self::getUser($connection);

			self::$_channels[$channelName]->addUser($user);
			$connection->send(":{$user->getNick()}!~{$user->getNick()}@{$user->getHost()} JOIN {$channelName}\n\r");

			//send channel topic
			if ($channel->hasTopic()) {
				$connection->send(":localhost.localdomain 332 {$user->getNick()} {$channelName} :{$channel->getTopic()}\n\r");//TODO add servername prefix
			} else {
				$connection->send("331 {$channelName} :No topic is set\n\r");
			}

			//send channel users
			$connection->send(":localhost.localdomain 353 {$user->getNick()} = {$channelName} :{$channel->getUsersString()}\n\r");
			$connection->send(":localhost.localdomain 366 {$user->getNick()} {$channelName} :End of /NAMES list.\n\r");
		} else {}
	}

	/**
	 * Creating channel
	 *
	 * @param string $channelName
	 * @param $connection
	 * @return Channel
	 */
	public static function createChannel(string $channelName, TcpConnection $connection):Channel
	{
		self::$_channels[$channelName] = new Channel($channelName);
		print "Channel {$channelName} created\n";
		return self::$_channels[$channelName];
	}

	/**
	 * Get User information
	 *
	 * @param $connection
	 * @return User
	 */
	public static function getUser(TcpConnection $connection):User
	{
		return self::$_users[$connection->id];
	}

	/**
	 * Get channel modes
	 *
	 * @param string $channelName
	 * @param $connection
	 */
	public static function getChannelModes(string $channelName, TcpConnection $connection)
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
	public static function getChannelUsers(string $channelName, TcpConnection $connection)
	{
		$users = self::$_channels[$channelName]->getUsers();
		foreach ($users as $user) {
			$connection->send("{$channelName} {$user->getNick()} {$user->getHost()} localhost.localdomain {$user->getNick()} H :0 realname\n\r");//TODO Fix
		}
		$user = self::getUser($connection);
		$connection->send("{$user->getNick()} {$channelName} :End of /WHO list.\n\r");//TODO Debug
	}

	/**
	 * Send message to channel
	 *
	 * @param array $params
	 * @param TcpConnection $connection
	 */
	public static function sendMessage(array $params, TcpConnection $connection)
	{
		$user = self::getUser($connection);
		$connection->send(":{$user->getNick()}!~{$user->getHost()} PRIVMSG {$params['receiver']} {$params['message']}\n\r");
	}

	/**
	 *
	 *
	 * @param TcpConnection $connection
	 */
	public static function getChannelsList(TcpConnection $connection)
	{
		$user = self::getUser($connection);
		$connection->send(":localhost.localdomain 321 {$user->getNick()} Channel :Users  Name\n\r");

		foreach (self::$_channels as $channel) {
			$connection->send(":localhost.localdomain 322 {$user->getNick()} {$channel->getName()} {$channel->getUsersCount()} :{$channel->getTopic()}\n\r");
		}

		$connection->send(":localhost.localdomain 323 {$user->getNick()} :End of /LIST\n\r");
	}
}