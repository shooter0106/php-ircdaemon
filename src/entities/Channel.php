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
	 * Get users on this channel
	 *
	 * @return array
	 */
	public function getUsers():array
	{
		return $this->_users;
	}

	/**
	 * Get users on this channel as string
	 *
	 * @return string
	 */
	public function getUsersString():string
	{
		return implode(' ', $this->_users);
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
		if (!empty($this->_topic)) {
			return $this->_topic;
		} else {
			return '';
		}
	}

	/**
	 * Check exists channel topic
	 *
	 * @return bool
	 */
	public function hasTopic():bool
	{
		return !empty($this->_topic);
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
	 * Returns modes string of this channel
	 *
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

	public function sendMessage()
	{
		foreach ($this->_users as $user) {

		}
	}
}