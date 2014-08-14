# Phile Markdown Editor Plugin #


Provides an online Markdown editor and file manager for Phile.

This is a rewrite/fork of [Pico-Editor-Plugin](https://github.com/gilbitron/Pico-Editor-Plugin) 1.1 for Phile.

[Project Home](https://github.com/Schlaefer/phileMarkdownEditor)

### 1.1 Installation (composer) ###


	php composer.phar require siezi/phile-markdown-editor:*

### 1.2 Installation (Download)

* Install [Phile](https://github.com/PhileCMS/Phile)
* Clone this repo into `plugins/siezi/markdownEditor`

### 2. Activation

After you have installed the plugin. You need to add the following line to your `config.php` file:


	$config['plugins']['siezi\\markdownEditor'] = array('active' => true);


### 3. Set a password ###

1. goto `admin/password` and create a password hash
2. put this hash into `admin/config.php` as password

### 4. Login ###

1. goto `admin/index` to login

