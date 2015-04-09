<?php

namespace Phile\Plugin\Siezi\PhileMarkdownEditor;

use Phile\Model\Page as PhilePage;

class Page extends PhilePage
{

    public static function filePropertiesFromPage(PhilePage $page)
    {
        $folder = dirname(str_replace(CONTENT_DIR, '', $page->getFilePath()));
        $file = basename($page->getFilePath());
        if ($folder === '.') {
            $folder = '/';
        };
        $properties = [
            'file' => $file,
            'folder' => $folder,
            'title' => $page->getTitle(),
            'url' => $page->getUrl()
        ];

        return $properties;
    }

}

