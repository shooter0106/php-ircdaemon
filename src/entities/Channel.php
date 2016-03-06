<?php

namespace IRCPHP\Entities;

class Channel
{
	private $channelName = '';
	private $_topic = '';
	private $_users = [];
	private $_usersCount = 0;
	private $_modes = ['n', 'r'];

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
		$this->_usersCount++;
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

	/**
	 * Returns name of this channel
	 *
	 * @return string
	 */
	public function getName():string
	{
		return $this->channelName;
	}

	/**
	 * Returns topic of this channel
	 *
	 * @return string
	 */
	public function getTopic():string
	{
		return $this->_topic;
	}

	/**
	 * Returns count users of this channel
	 *
	 * @return int
	 */
	public function getUsersCount():int
	{
		return $this->_usersCount;
	}

	/**
	 * @return string
	 */
	public function getModes()
	{
		$modesString = '+';
		foreach ($this->_modes as $mode) {
			$modesString .= $mode;
		}
		return $modesString;
	}


}