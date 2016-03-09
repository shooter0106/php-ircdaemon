<?php

namespace IRCPHP;

use IRCPHP\Entities\User;
use IRCPHP\Entities\Channel;
use IRCPHP\Entities\UserSlot;
use Workerman\Connection\TcpConnection;

class Server
{
	private static $_users = [];
	private static $_channels = [];

	/**
	 * Create User instance
	 *
	 * @param array $params
	 * @param TcpConnection $connection
	 */
	public static function createUser(array $params, TcpConnection $connection)
	{
		if (self::$_users[$connection->id] instanceof UserSlot) {
			$newParams = $params;
			$newParams['nickname'] = self::$_users[$connection->id]->getNick();
			self::$_users[$connection->id] = new User($newParams, $connection);
		}
	}

	/**
	 * Destruct User instance
	 *
	 * @param TcpConnection $connection
	 */
	public static function destroyUser(TcpConnection $connection)
	{
		unset(self::$_users[$connection->id]);
	}

	/**
	 * Change User nickname
	 *
	 * @param TcpConnection $connection
	 * @param string $nick
	 */
	public static function changeUserNick(string $nick, TcpConnection $connection)
	{
		if (isset(self::$_users[$connection->id])) {
			self::$_users[$connection->id]->changeNick($nick);
		} else {
			self::$_users[$connection->id] = new UserSlot($nick, $connection);
		}
	}

	/**
	 * Join to channel
	 *
	 * @param string $channelName
	 * @param $connection
	 */
	public static function joinChannel(string $channelName, TcpConnection $connection)
	{
		$join = function (Channel $channel, User $user, TcpConnection $connection) {
			self::$_channels[$channel->getName()]->addUser($user);
			$connection->send(":{$user->getNick()}!~{$user->getNick()}@{$user->getHost()} JOIN {$channel->getName()}\n\r");

			//send channel topic
			if ($channel->hasTopic()) {
				$connection->send(":localhost.localdomain 332 {$user->getNick()} {$channel->getName()} :{$channel->getTopic()}\n\r");//TODO add servername prefix
			} else {
				$connection->send("331 {$channel->getName()} :No topic is set\n\r");
			}

			//send channel users
			$connection->send(":localhost.localdomain 353 {$user->getNick()} = {$channel->getName()} :{$channel->getUsersString()}\n\r");
			$connection->send(":localhost.localdomain 366 {$user->getNick()} {$channel->getName()} :End of /NAMES list.\n\r");

			//send join tonify to all users
			$currentUser = self::$_users[$connection->id];
			foreach (self::$_users as $user) {
				if ($user->getNick() == $currentUser->getNick()) {
					continue;
				} else {
					$user->getConnection()->send(":{$currentUser->getNick()}!~{$currentUser->getHost()} JOIN {$channel->getName()}\n\r");
				}
			}
		};

		$user = self::getUser($connection);
		if (!isset(self::$_channels[$channelName])) {
			$channel = self::createChannel($channelName, $connection);
			$join($channel, $user, $connection);
		} else {
			$channel = self::getChannelByName($channelName);
			$join($channel, $user, $connection);
		}
	}

	/**
	 * Return channel object by his name
	 *
	 * @param string $name
	 * @return Channel
	 */
	public static function getChannelByName(string $name):Channel
	{
		return self::$_channels[$name];
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
	 * Send message for all users at channel
	 *
	 * @param string $receiver
	 * @param string $message
	 * @param TcpConnection $connection
	 */
	public static function sendMessage(string $receiver, string $message, TcpConnection $connection)
	{
		$currentUser = self::$_users[$connection->id];
		foreach (self::$_users as $user) {
			if ($user->getNick() == $currentUser->getNick()) {
				continue;
			} else {
				$user->getConnection()->send(":{$currentUser->getNick()}!~{$currentUser->getHost()} PRIVMSG {$receiver} {$message}\n\r");
			}
		}
	}

	/**
	 * Send server's channel list to client
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