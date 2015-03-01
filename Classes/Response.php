<?php

	namespace Phile\Plugin\Siezi\PhileMarkdownEditor;

	/**
	 * Class Response
	 *
	 * @author Schlaefer <openmail+sourcecode@siezi.com>
	 * @link https://github.com/Schlaefer/phileMarkdownEditor
	 * @license http://opensource.org/licenses/MIT
	 * @package Phile\Plugin\Siezi\PhileMarkdownEditor
	 */
	class Response extends \Phile\Core\Response{

		protected $_base;

		protected $_baseUrl;

		protected $_headers = [];

		public function __construct($baseUrl, $base) {
			$this->_baseUrl = $baseUrl;
			$this->_base = $base;
		}

		public function redirect($action) {
			header('Location: ' . $this->_baseUrl . '/' . $this->_base . '/' . $action);
			$this->stop();
		}

		public function send() {
			foreach($this->_headers as $header) {
				header($header);
			}
			parent::send();
			$this->stop();
		}

		public function stop() {
			exit;
		}

		public function type($type) {
			switch ($type) {
				case 'json':
					$this->headers[] = 'Content-Type: application/json';
			}
		}

	}

