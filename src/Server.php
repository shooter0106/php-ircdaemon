<?php

namespace IRCPHP;

use IRCPHP\Entities\User;

class Server
{
	private $_users = [];

	public function createUser(string $nick)
	{
		$this->_users[] = new User($nick);
	}
}