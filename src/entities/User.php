<?php

namespace IRCPHP\Entities;

class User
{
	private $_nick = null;

	/**
	 * User constructor.
	 *
	 * @param string $nick
	 * @param int $conId
	 */
	public function __construct(string $nick, int $conId)
	{
		$this->_nick = $nick;
		print "User {$this->_nick} connected.\n";
	}

	/**
	 * User destructor.
	 */
	public function __destruct()
	{
		print "User {$this->_nick} disconnected.\n";
	}
}