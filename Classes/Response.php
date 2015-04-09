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
class Response extends \Phile\Core\Response
{

    protected $_base;

    protected $_baseUrl;

    public function __construct($baseUrl, $base)
    {
        $this->_baseUrl = $baseUrl;
        $this->_base = $base;
    }

    public function redirect($action, $statusCode = 302)
    {
        header('Location: ' . $this->_baseUrl . '/' . $this->_base . '/' . $action);
        $this->stop();
    }

    public function send()
    {
        parent::send();
        $this->stop();
    }

    public function type($type)
    {
        switch ($type) {
            case 'json':
                $this->setHeader('Content-Type', 'application/json');
        }
    }

}

