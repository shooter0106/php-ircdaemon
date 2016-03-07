<?php
namespace IRCPHP\Entities;


use Workerman\Connection\TcpConnection;

class UserSlot
{
	private $_nick = null;
	private $_connectionId = 0;

	/**
	 * UserSlot constructor.
	 *
	 * @param null|string $_nick
	 * @param TcpConnection $connection
	 */
	public function __construct(string $_nick, TcpConnection $connection)
	{
		$this->_nick = $_nick;
		$this->_connectionId = $connection->id;
	}

	public function getNick():string
	{
		if (!empty($this->_nick)) {
			return $this->_nick;
		}
	}
}