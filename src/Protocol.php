<?php

namespace IRCPHP;

use IRCPHP\Entities\User;

class Protocol
{
	private $_protocol = [
		'NICK', 'USER', 'QUIT', 'JOIN', 'MODE', 'WHO', 'PRIVMSG'
	];
	private $_commands = [];
	private $_connection = null;

	/**
	 * Preapres client raw input
	 *
	 * @param string $input
	 */
	public function readClientMessage(string $input, $connection)
	{
		$this->_connection = $connection;
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
						$this->_connection->send("*** Welcome to the shooter's PHP IRC server!!! ***\n\r");
						Server::createUser([
							'username' => $tmp['params'][0],
							'servername' => $tmp['params'][2],
							'realname' => $tmp['params'][3],
						], $this->_connection);
						break;
					case 'QUIT':
						Server::destroyUser($this->_connection);
						break;
					case 'JOIN':
						Server::joinChannel($tmp['params'][0], $this->_connection);
						break;
					case 'MODE':
						Server::getChannelModes($tmp['params'][0], $this->_connection);
						break;
					case 'WHO':
						Server::getChannelUsers($tmp['params'][0], $this->_connection);
						break;
					case 'PRIVMSG':

						break;
				}
			}
		}
	}
}