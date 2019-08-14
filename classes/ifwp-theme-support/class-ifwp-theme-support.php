<?php

	if(class_exists('IFWP_Theme_Support', false)){
		deactivate_plugins(plugin_basename(IFWP));
		wp_die('<strong>ERROR</strong>: IFWP_Theme_Support class already exists.', 'IFWP &rsaquo; error');
	} else {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	class IFWP_Theme_Support {

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
        ifwp('error')->doing_it_wrong(__CLASS__ . '::' . $name, 'Method does not exist.');
		return;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// static methods
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static private $sizes = array();

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static function image_size_names_choose($sizes){
        if(self::$sizes){
            foreach(self::$sizes as $key => $value){
        		if(!isset($sizes[$key])){
                    $sizes[$key] = $value;
                }
        	}
        }
    	return $sizes;
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// methods
	//
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function 4k_thumbnails($atts = array()){
		$defaults = array(
			'beaver_builder_plugin' => true,
		);
		$atts = shortcode_atts($defaults, $atts);
        $this->thumbnails();
        add_image_size('4k', 3840, 3840);
		if($atts['beaver_builder_theme']){
			self::$sizes['4k'] = '4K';
			if(!has_action('image_size_names_choose', array(__CLASS__, 'image_size_names_choose'))){
				add_action('image_size_names_choose', array(__CLASS__, 'image_size_names_choose'));
			}
		}
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function full_hd_thumbnails($atts = array()){
		$defaults = array(
			'beaver_builder_plugin' => true,
		);
		$atts = shortcode_atts($defaults, $atts);
        $this->thumbnails();
        add_image_size('full-hd', 1920, 1920);
		if($atts['beaver_builder_theme']){
			self::$sizes['full-hd'] = 'Full HD';
			if(!has_action('image_size_names_choose', array(__CLASS__, 'image_size_names_choose'))){
				add_action('image_size_names_choose', array(__CLASS__, 'image_size_names_choose'));
			}
		}
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function hd_thumbnails($atts = array()){
		$defaults = array(
			'beaver_builder_plugin' => true,
		);
		$atts = shortcode_atts($defaults, $atts);
        $this->thumbnails();
        add_image_size('hd', 1280, 1280);
		if($atts['beaver_builder_theme']){
			self::$sizes['hd'] = 'HD';
			if(!has_action('image_size_names_choose', array(__CLASS__, 'image_size_names_choose'))){
				add_action('image_size_names_choose', array(__CLASS__, 'image_size_names_choose'));
			}
		}
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function thumbnails(){
        add_theme_support('post-thumbnails');
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
