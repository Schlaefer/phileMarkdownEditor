<?php

	namespace Phile\Plugin\Siezi\PhileMarkdownEditor;

	use Phile\Exception;

	include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'password.php';

	class Auth {

		const SESSION_KEY = 'sieziMarkdownEditor';

		protected $_hash;

		protected $_Request;

		public function __construct(Request $Request, $hash) {
			$this->_Request = $Request;
			$this->_hash = $hash;
		}

		public function auth() {
			if (!session_id()) {
				session_start();
			}
			if (!$this->authEnabled())  {
				return false;
			}
			if ($this->_sessionLogin()) {
				return true;
			}
			if ($this->_formLogin()) {
				return true;
			}
			return false;
		}

		public function hash($password) {
			return password_hash($password, PASSWORD_BCRYPT);
		}

		public function logout() {
			if (session_id()) {
				session_destroy();
			}
		}

		public function authEnabled() {
			return !empty($this->_hash);
		}

		protected function _sessionLogin() {
			if (!empty($_SESSION[self::SESSION_KEY])) {
				return true;
			}
			return false;
		}

		protected function _formLogin() {
			$password = $this->_Request->param('password');
			if (!$password) {
				return false;
			}
			if (password_verify($password, $this->_hash)) {
				$_SESSION[self::SESSION_KEY] = true;
				return true;
			}
			return false;
		}

	}
