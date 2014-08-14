<?php

	namespace Phile\Plugin\Siezi\MarkdownEditor;

	use Phile\Exception;

	class Plugin extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {

		protected $_allowedActions = [
			'login',
			'logout',
			'password'
		];

		protected $_Auth;

		protected $_Request;

		protected $_Response;

		protected $_phile;

		protected $_pluginPath;

		protected $_TemplateEngine;

		public function __construct() {
			\Phile\Event::registerEvent('request_uri', $this);
			\Phile\Event::registerEvent('template_engine_registered', $this);

			$this->_pluginPath = dirname(dirname(__FILE__));
			$this->_Request = new Request($_REQUEST, $this->settings['uri']);
		}

		public function on($eventKey, $data = null) {
			if ($eventKey === 'request_uri') {
				$this->_Request->setUri($data['uri']);
				return;
			}

			// current page is not an editor page
			if (!$this->_Request->isEditor()) {
				return;
			}

			if ($eventKey === 'template_engine_registered') {
				$this->_phile = $data['data'];
				$this->_Auth = new Auth();
				$this->_Response = new Response($this->_phile['base_url'],
					$this->settings['uri']);

				$loader = new \Twig_Loader_Filesystem($this->_pluginPath);
				$this->_TemplateEngine = new \Twig_Environment($loader, $this->_phile);

				$this->_dispatch();
			}
		}

		public function index() {
			$this->_render('editor');
		}

		public function login() {
			$this->_render('login');
		}

		public function logout() {
			$this->_Auth->logout();
			$this->_Response->redirect('login');
		}

		public function create() {
			$title = $this->_Request->param('title');
			$content = '<!--
Title: ' . $title . '
Author:
Date: ' . date('Y-m-d') . '
-->';

			$file = new ContentFile();
			$error = '';
			try {
				$file->create($title, $content);
			} catch (Exception $e) {
				$error = 'Error: creating posting failed';
			}

			$this->_Response->type('json');
			$this->_Response->body = json_encode(array(
				'title' => $title,
				'content' => $content,
				'file' => $file->getFilename(),
				'error' => $error
			));
		}

		public function destroy() {
			$title = $this->_Request->param('file');
			$file = new ContentFile($title);
			$file->delete();
		}

		public function open() {
			$title = $this->_Request->param('file');
			$file = new ContentFile($title);
			$this->_Response->body = $file->read();
		}

		public function save() {
			$content = $this->_Request->param('content');
			if (!$content) {
				throw new Exception();
			}
			$title = $this->_Request->param('file');
			$file = new ContentFile($title);
			$file->write($content);

			$this->_Response->body = $content;
		}

		public function password() {
			$data = [];
			$passwordHash = $this->_Request->param('passwordToHash');
			if ($passwordHash) {
				$data = [
					'hashedPassword' => $this->_Auth->hash($passwordHash)
				];
			}
			$this->_render('password', $data);
		}

		protected function _file($fileUrl) {
			$file = basename(strip_tags($fileUrl));
			if(!$file) die('Error: Invalid file');

			$file = CONTENT_DIR . $file . CONTENT_EXT;
			if (!file_exists($file)) {
				throw new \Exception;
			}
			return $file;
		}

		protected function _slugify($text) {
			// replace non letter or digits by -
			$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

			// trim
			$text = trim($text, '-');

			// transliterate
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

			// lowercase
			$text = strtolower($text);

			// remove unwanted characters
			$text = preg_replace('~[^-\w]+~', '', $text);

			if (empty($text)) {
				return 'n-a';
			}

			return $text;
		}

		protected function _dispatch() {
			$action = $this->_Request->getAction();

			$reflection = new \ReflectionMethod($this, $action);
			if ($action === 'on' || !$reflection->isPublic()) {
				// page not found
				return;
			}

			$authorized = $this->_Auth->auth($this->_Request, $this->settings['password']);
			if (in_array($action, $this->_allowedActions)) {
				if ($action === 'login' && $authorized) {
					$this->_Response->redirect('index');
				}
			} elseif (!$authorized) {
				$this->_Response->redirect('login');
			}

			$this->$action();
			$this->_Response->send();
		}

		protected function _render($file, $vars = []) {
			$vars += $this->_phile;
			$vars += ['pluginUrl' => $this->_phile['base_url'] . '/plugins/siezi/markdownEditor'];
			$this->_Response->body = $this->_TemplateEngine->render(
				'pages' . DIRECTORY_SEPARATOR . $file . '.twig', $vars);
		}


	}