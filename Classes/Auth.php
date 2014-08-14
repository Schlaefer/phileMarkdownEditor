<?php

	namespace Phile\Plugin\Siezi\MarkdownEditor;

	include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'password.php';

	class Auth {

		const SESSION_KEY = 'sieziMarkdownEditor';

		public function auth($Request, $hash) {
			if (!session_id()) {
				session_start();
			}
			if ($this->_sessionLogin()) {
				return true;
			}
			if ($this->_formLogin($Request, $hash)) {
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

		protected function _sessionLogin() {
			if (!empty($_SESSION[self::SESSION_KEY])) {
				return true;
			}
			return false;
		}

		protected function _formLogin($Request, $hash) {
			$password = $Request->param('password');
			if (!$password) {
				return false;
			}
			if (password_verify($password, $hash)) {
				$_SESSION[self::SESSION_KEY] = true;
				return true;
			}
			return false;
		}

	}
