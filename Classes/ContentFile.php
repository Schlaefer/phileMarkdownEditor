<?php

	namespace Phile\Plugin\Siezi\PhileMarkdownEditor;

	use Phile\Exception;
	use Phile\Repository\Page;

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

		protected $_fullPath;

		public function __construct($filename = null) {
			$this->_filename = $filename;
		}

		public function create($title, $content) {
			if (empty($title)) {
				throw new \InvalidArgumentException;
			}
			$this->_filename = $this->_slug(basename($title));
			$this->_fullPath = CONTENT_DIR . $this->_filename . CONTENT_EXT;

			if ($this->exists()) {
				throw new Exception("File '$this->_filename' already exists.");
			}
			file_put_contents($this->_getFullPath(), $content);
		}

		public function getFilename() {
			return basename($this->_filename);
		}

		public function exists() {
			return file_exists($this->_getFullPath());
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
			$file = $this->_getFullPath();
			if (!file_exists($file)) {
				throw new \Exception;
			}
			return $file;
		}

		protected function _getFullPath() {
			if (isset($this->_fullPath)) {
				return $this->_fullPath;
			}

			// filename for root index is empty string
			if (!is_string($this->_filename) && empty($this->_filename)) {
				throw new \RuntimeException('Filename not set');
			}

			$PageRepository = new Page();
			$this->_fullPath = $PageRepository->findByPath($this->_filename)
				->getFilePath();
			return $this->_fullPath;
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
