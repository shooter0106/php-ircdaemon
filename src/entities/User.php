<?php

namespace IRCPHP\Entities;

use Workerman\Connection\TcpConnection;

class User
{
	private $_nick, $_host, $_realName, $_connectionId = null;

	/**
	 * User constructor.
	 *
	 * @param array $params
	 * @param TcpConnection $connection
	 */
	public function __construct(array $params, TcpConnection $connection)
	{
		$this->_nick = $params['username'];
		$this->_host = $connection->getRemoteIp();
		$this->_realName = $params['realname'];

		print "User {$this->_nick} connected.\n";
	}

	/**
	 * User destructor.
	 */
	public function __destruct()
	{
		print "User {$this->_nick} disconnected.\n";
	}

	/**
	 * Returns nick of the user
	 *
	 * @return string
	 */
	public function getNick():string
	{
		return $this->_nick;
	}

	/**
	 * Returns host of the user
	 *
	 * @return string
	 */
	public function getHost():string
	{
		return $this->_host;
	}

	/**
	 * Returns real name of user
	 *
	 * @return string
	 */
	public function getRealName():string
	{
		return $this->_realName;
	}

	/**
	 * Implements toString magic method
	 *
	 * @return mixed
	 */
	public function __toString()
	{
		return $this->_nick;
	}
}