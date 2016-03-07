<?php

namespace IRCPHP\Entities;

class User
{
	private $_nick, $_host, $_servername, $_realname = null;

	/**
	 * User constructor.
	 *
	 * @param array $params
	 */
	public function __construct(array $params, $connection)
	{
		$this->_nick = $params['username'];
		$this->_host = $connection->getRemoteIp();
		$this->_servername = $params['servername'];
		$this->_realname = $params['realname'];

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
	public function getServername():string
	{
		return $this->_servername;
	}

	/**
	 * @return string
	 */
	public function getRealname():string
	{
		return $this->_realname;
	}

	public function __toString()
	{
		return $this->_nick;
	}
}