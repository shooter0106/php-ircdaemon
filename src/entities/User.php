<?php

namespace IRCPHP\Entities;

class User
{
	private $_nick, $_host, $_serverName, $_realName = null;

	/**
	 * User constructor.
	 *
	 * @param array $params
	 */
	public function __construct(array $params, $connection)
	{
		$this->_nick = $params['username'];
		$this->_host = $connection->getRemoteIp();
		$this->_serverName = $params['servername'];
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
	 * @return string
	 */
	public function getNick():string
	{
		return $this->_nick;
	}

	/**
	 * @return string
	 */
	public function getHost():string
	{
		return $this->_host;
	}

	/**
	 * @return string
	 */
	public function getServerName():string
	{
		return $this->_serverName;
	}

	/**
	 * @return string
	 */
	public function getRealName():string
	{
		return $this->_realName;
	}

	public function __toString()
	{
		return $this->_nick;
	}
}