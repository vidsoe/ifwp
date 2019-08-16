<?php

	if(class_exists('IFWP_CF7', false)){
		deactivate_plugins(plugin_basename(IFWP));
		wp_die('<strong>ERROR</strong>: IFWP_CF7 class already exists.', 'IFWP &rsaquo; error');
	} else {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	class IFWP_CF7 {

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

    static private $additional_settings = false;

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static private function is_cf7_edit(){
        global $pagenow;
        if(is_admin() and $pagenow == 'post.php' and isset($_GET['_wpnonce'], $_GET['action'], $_GET['post']) and wp_verify_nonce($_GET['_wpnonce'], 'ifwp_cf7_edit_' . $_GET['post']) and $_GET['action'] == 'edit' and get_post_type($_GET['post']) == WPCF7_ContactForm::post_type){
            return true;
        }
        return false;
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static function admin_notices(){
        global $pagenow;
        if(self::$additional_settings){
            if(self::is_cf7_edit()){
                if(!class_exists('MB_Conditional_Logic')){
                    printf('<div class="update-nag"><p>' . esc_html('%1$s recommends %2$s for a better user experience.') . '</p></div>', '<strong>IFWP</strong>', '<strong>Meta Box Conditional Logic</strong>');
                }
            }
        }
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static function panel($contact_form){
		$nonce_url = wp_nonce_url(get_edit_post_link($contact_form->id()), 'ifwp_cf7_edit_' . $contact_form->id());
		echo '<h2>Additional Settings</h2><fieldset><legend><a href="' . $nonce_url . '">Edit</a>.</legend></fieldset>';
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static function register_post_type_args($args, $post_types){
        if(self::$additional_settings){
            if(self::is_cf7_edit()){
                $args['show_in_menu'] = false;
				$args['show_ui'] = true;
                $args['supports'] = array('revisions');
            }
        }
    	return $args;
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static function rwmb_meta_boxes($meta_boxes){
        if(self::$additional_settings){
			if(self::is_cf7_edit()){
				$meta_boxes[] = array(
					'id' => 'ifwp-additional-settings',
					'name' => 'Additional Settings',
					'fields' => array(
			            array(
							'id' => 'prueba',
			                'name' => 'Prueba',
			                'type' => 'text',
			            ),
			        ),
					'post_types' => WPCF7_ContactForm::post_type,
				);
			}
        }
    	return $meta_boxes;
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static function wpcf7_editor_panels($panels){
		if(self::$additional_settings){
			$panels['ifwp'] = array(
				'callback' => array(__CLASS__, 'panel'),
				'title' => 'IFWP',
			);
		}
		return $panels;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// methods
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function additional_settings(){
		if(class_exists('WPCF7_ContactForm')){
			self::$additional_settings = true;
	        if(!has_action('admin_notices', array(__CLASS__, 'admin_notices'))){
	            add_action('admin_notices', array(__CLASS__, 'admin_notices'));
	        }
	        if(!has_action('register_post_type_args', array(__CLASS__, 'register_post_type_args'), 10, 2)){
	            add_action('register_post_type_args', array(__CLASS__, 'register_post_type_args'), 10, 2);
	        }
			if(!has_filter('rwmb_meta_boxes', array(__CLASS__, 'rwmb_meta_boxes'))){
	            add_filter('rwmb_meta_boxes', array(__CLASS__, 'rwmb_meta_boxes'));
	        }
			if(!has_filter('wpcf7_editor_panels', array(__CLASS__, 'wpcf7_editor_panels'))){
	            add_filter('wpcf7_editor_panels', array(__CLASS__, 'wpcf7_editor_panels'));
	        }
		}
    }

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
