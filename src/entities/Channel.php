<?php

namespace IRCPHP\Entities;

class Channel
{
	private $channelName = '';
	private $_topic = '';
	private $_users = [];

	/**
	 * Channel constructor.
	 *
	 * @param string $channelName
	 */
	public function __construct(string $channelName)
	{
		$this->channelName = $channelName;
	}

	public function addUser(User $user)
	{
		$this->_users[$user->getNick()] = $user;
	}

	public function getUsers():array
	{
		return $this->_users;
	}
}