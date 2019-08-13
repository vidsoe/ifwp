<?php

	if(class_exists('IFWP_Error', false)){
		deactivate_plugins(plugin_basename(IFWP));
		wp_die('<strong>ERROR</strong>: IFWP_Error class already exists.', 'IFWP &rsaquo; error');
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	class IFWP_Error {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static private $instance = null;

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static function get_instance(){
		if(!self::$instance instanceof self){
			self::$instance = new self;
		}
		return self::$instance;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function __call($name, $arguments){
		$this->doing_it_wrong($name, 'Class does not exist.');
		return;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// Methods
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function doing_it_wrong($function = '', $message = ''){
		if(WP_DEBUG){
			trigger_error(sprintf(__('%1$s was called <strong>incorrectly</strong>. %2$s'), $function, $message));
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
