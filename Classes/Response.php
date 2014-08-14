<?php

	namespace Phile\Plugin\Siezi\PhileMarkdownEditor;

	class Response {

		public $body = '';

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
			// Override Phile's 404 header
			$this->_headers[] = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
			foreach($this->_headers as $header) {
				header($header);
			}
			echo $this->body;
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

