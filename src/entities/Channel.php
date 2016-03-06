<?php

namespace IRCPHP\Entities;

class Channel
{
	private $channelName = '';
	private $_topic = '';

	/**
	 * Channel constructor.
	 *
	 * @param string $channelName
	 */
	public function __construct(string $channelName)
	{
		$this->channelName = $channelName;
	}
}