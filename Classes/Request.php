<?php

	namespace Phile\Plugin\Siezi\PhileMarkdownEditor;

	class Request {

		protected $_base;

		protected $_request;

		protected $_uri;

		public function __construct($request) {
			$this->_request = $request;
		}

		public function param($key) {
			if (isset($this->_request[$key])) {
				return $this->_request[$key];
			}
			return null;
		}

		public function setUri($uri) {
			$this->_uri = $uri;
		}

		public function setbase($base) {
			$this->_base = $base;
		}

		public function isEditor() {
			return !empty($this->_uri) && $this->getAction();
		}

		public function getAction() {
			$uri = rtrim($this->_uri, '/');
			$base = rtrim($this->_base, '/');

			preg_match("/$base\/(?P<action>.*)(\/)?$/", $uri, $matches);
			if (!empty($matches['action'])) {
				return $matches['action'];
			}

			return false;
		}

	}

