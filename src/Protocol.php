<?php

namespace IRCPHP;

use IRCPHP\Entities\User;

class Protocol
{
	private $_protocol = [
		'CAP', 'NICK', 'USER', 'QUIT'
	];
	private $_commands = [];
	private $_connectionID = 0;

	/**
	 * Preapres client raw input
	 *
	 * @param string $input
	 * $@param int $conID
	 */
	public function readClientMessage(string $input, int $conID)
	{
		$this->_connectionID = $conID;
		$commands = explode("\r", $input);
		foreach ($commands as &$message) {
			$message = trim(str_replace(["\r\n", "\n", "\r"], '', $message));
		}
		$this->_commands = array_filter($commands, function ($msg) {
			if (!empty($msg)) {
				return true;
			} else {
				return false;
			}
		});
	}

	/**
	 * Parse client input for command and her arguments
	 *
	 * @param string $input
	 * @return array
	 */
	private function parseCommand(string $input)
	{
		$cmd = explode(' ', $input);

		$command = $cmd[0];
		array_shift($cmd);
		$params = $cmd;

		return [
			'cmd' => $command,
			'params' => $params
		];
	}

	/**
	 * Execute commands sended by client
	 *
	 */
	public function execCommands()
	{
		foreach ($this->_commands as $command) {
			$tmp = $this->parseCommand($command);
			if (in_array($tmp['cmd'], $this->_protocol)) {
				switch ($tmp['cmd']) {
					/*case 'NICK':
						Server::createUser($tmp['params'][0]);
						break;*/
					case 'USER':
						Server::createUser($tmp['params'][0], $this->_connectionID);
						break;
					case 'QUIT':
						Server::destroyUser($this->_connectionID);
						break;
				}
			}
		}
	}
}