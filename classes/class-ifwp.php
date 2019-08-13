<?php

	class IFWP {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static private $instance = null;

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function get_instance(){
		if(!self::$instance instanceof self){
			self::$instance = new self;
		}
		return self::$instance;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	public function __call($name, $arguments){
        ifwp('error')->trigger(__CLASS__ . '::' . $name, 'Method does not exist.');
		return;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	public function include($path = ''){
		$file = plugin_dir_path(IFWP) . 'includes/' . $path;
		if(file_exists($file)){
			require_once($file);
		} else {
			ifwp('error')->trigger(__METHOD__, 'File does not exist: ' . $path);
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	public function includes_url($path = ''){
		$file = plugin_dir_path(IFWP) . 'includes/' . $path;
		if(file_exists($file)){
			return plugin_dir_url(IFWP) . 'includes/' . $path;
		} else {
			ifwp('error')->trigger(__METHOD__, 'File does not exist: ' . $path);
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	public function update(){
		$this->include('plugin-update-checker-4.7/plugin-update-checker.php');
		Puc_v4_Factory::buildUpdateChecker('https://github.com/vidsoe/ifwp', IFWP, 'ifwp');
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
