<?php

namespace IRCPHP\Entities;

use Workerman\Connection\TcpConnection;

class User
{
	private $_nick, $_host, $_realName, $_connection = null;

	/**
	 * User constructor.
	 *
	 * @param array $params
	 * @param TcpConnection $connection
	 */
	public function __construct(array $params, TcpConnection &$connection)
	{
		if (isset($params['nickname'])) {
			$this->_nick = $params['nickname'];
		}
		$this->_realName = $params['realname'];
		$this->_host = $connection->getRemoteIp();
		$this->_connection = $connection;

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
	public function __toString():string
	{
		return $this->_nick;
	}

	/**
	 * Returns user connection
	 *
	 * @return TcpConnection
	 */
	public function getConnection():TcpConnection
	{
		return $this->_connection;
	}

	public function changeNick(string $nick)
	{
		$this->_nick = $nick;
	}
}