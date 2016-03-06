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

	/**
	 * Add user to channel
	 *
	 * @param User $user
	 */
	public function addUser(User $user)
	{
		$this->_users[$user->getNick()] = $user;
	}

	/**
	 * Get usersn on channel
	 *
	 * @return array
	 */
	public function getUsers():array
	{
		return $this->_users;
	}
}