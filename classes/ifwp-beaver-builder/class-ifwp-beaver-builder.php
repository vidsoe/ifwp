<?php

	if(class_exists('IFWP_Beaver_Builder', false)){
		deactivate_plugins(plugin_basename(IFWP));
		wp_die('<strong>ERROR</strong>: IFWP_Beaver_Builder class already exists.', 'IFWP &rsaquo; error');
	} else {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	class IFWP_Beaver_Builder {

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

    static private $bootstrap_4_color_preset = array(), $bootstrap_4_color_preset_override = false, $disable_inline_editing = false, $disable_zoom = false;

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static function fl_builder_color_presets($colors){
        if(self::$bootstrap_4_color_preset){
            if(self::$bootstrap_4_color_preset_override){
                $colors = array();
            }
            $colors[] = '007bff'; // primary
            $colors[] = '6c757d'; // secondary
            $colors[] = '28a745'; // success
            $colors[] = 'dc3545'; // danger
            $colors[] = 'ffc107'; // warning
            $colors[] = '17a2b8'; // info
            $colors[] = 'f8f9fa'; // light
            $colors[] = '343a40'; // dark
        }
        return $colors;
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static function fl_inline_editing_enabled($show){
        if(self::$disable_inline_editing){
            return false;
        }
    	return $show;
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static function fl_theme_viewport($str){
        if(self::$disable_zoom){
            return "<meta name='viewport' content='width=device-width, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1, user-scalable=no' />\n";
        }
    	return $str;
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// methods
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function disable_zoom(){
		self::$disable_zoom = true;
        if(!has_filter('fl_theme_viewport', array(__CLASS__, 'fl_theme_viewport'))){
			add_filter('fl_theme_viewport', array(__CLASS__, 'fl_theme_viewport'));
		}
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function bootstrap_4_color_preset($override = false){
		self::$bootstrap_4_color_preset = true;
        self::$bootstrap_4_color_preset_override = $override;
        if(!has_filter('fl_builder_color_presets', array(__CLASS__, 'fl_builder_color_presets'))){
			add_filter('fl_builder_color_presets', array(__CLASS__, 'fl_builder_color_presets'));
		}
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function disable_inline_editing(){
		self::$disable_inline_editing = true;
        if(!has_filter('fl_inline_editing_enabled', array(__CLASS__, 'fl_inline_editing_enabled'))){
			add_filter('fl_inline_editing_enabled', array(__CLASS__, 'fl_inline_editing_enabled'));
		}
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
