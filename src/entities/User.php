<?php

namespace IRCPHP\Entities;

class User
{
	private $_nick = null;

	public function __construct(string $nick)
	{
		$this->_nick = $nick;
		print "User {$nick} connected.\n";
	}
}