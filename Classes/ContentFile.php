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
			$file = $this->_slugify(basename($title));
			if (!$title) {
				throw new \InvalidArgumentException;
			}
			$file .= CONTENT_EXT;
			$this->_filename = $file;
			if (file_exists(CONTENT_DIR . $this->_filename)) {
				throw new Exception;
			} else {
			}
			file_put_contents(CONTENT_DIR . $this->_filename, $content);
		}

		public function getFilename() {
			return basename(str_replace(CONTENT_EXT, '', $this->_filename));
		}

		public function delete() {
			unlink($this->_file($this->_filename));
		}

		public function read() {
			return file_get_contents($this->_file($this->_filename));
		}

		public function write($content) {
			if (!file_put_contents($this->_file($this->_filename), $content)) {
				throw new Exception();
			}
		}

		protected function _file($fileUrl) {
			$file = basename(strip_tags($fileUrl));
			if (!$file) {
				die('Error: Invalid file');
			}

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

	}
