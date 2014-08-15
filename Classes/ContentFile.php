<?php

	namespace Phile\Plugin\Siezi\PhileMarkdownEditor;

	use Phile\Exception;

	/**
	 * Class ContentFile
	 *
	 * @author Schlaefer <openmail+sourcecode@siezi.com>
	 * @link https://github.com/Schlaefer/phileMarkdownEditor
	 * @license http://opensource.org/licenses/MIT
	 * @package Phile\Plugin\Siezi\PhileMarkdownEditor
	 */
	class ContentFile {

		protected $_filename;

		public function __construct($filename = null) {
			$this->_filename = $filename;
		}

		public function create($title, $content) {
			if (empty($title)) {
				throw new \InvalidArgumentException;
			}
			$this->_filename = $this->_slug(basename($title));
			if ($this->exists()) {
				throw new Exception("File '$this->_filename' already exists.");
			}
			file_put_contents($this->_fullPath(), $content);
		}

		public function getFilename() {
			return basename($this->_filename);
		}

		public function exists() {
			return file_exists($this->_fullPath());
		}

		public function delete() {
			unlink($this->_file());
		}

		public function read() {
			return file_get_contents($this->_file());
		}

		public function write($content) {
			if (!file_put_contents($this->_file(), $content)) {
				throw new Exception();
			}
		}

		protected function _file() {
			$file = $this->_fullPath();
			if (!file_exists($file)) {
				throw new \Exception;
			}
			return $file;
		}

		protected function _fullPath() {
			if (empty($this->_filename)) {
				throw new \RuntimeException('Filename not set');
			}
			return CONTENT_DIR . $this->_filename . CONTENT_EXT;
		}

		protected function _slug($text) {
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

	}
