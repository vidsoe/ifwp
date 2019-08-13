<?php
/**
 * Author: Vidsoe
 * Author URI: https://vidsoe.com
 * Description: Improvements and Fixes for WordPress
 * Domain Path:
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network:
 * Plugin Name: IFWP
 * Plugin URI: https://ifwp.vidsoe.com
 * Text Domain: ifwp
 * Version: 2019.8.11.13
 *
 */ // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	defined('ABSPATH') or die('No script kiddies please!');

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    add_action('plugins_loaded', function(){
        if(defined('IFWP') or function_exists('ifwp')){
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die('<strong>ERROR</strong>: IFWP already exists.', 'IFWP &rsaquo; error');
        } else {
            define('IFWP', __FILE__);
            function ifwp($class = 'ifwp'){
        		if($class != 'ifwp'){
        			$class = 'ifwp_' . str_replace('-', '_', sanitize_title($class));
        			if(!class_exists($class, false)){
        				$file = plugin_dir_path(IFWP) . 'classes/class-' . str_replace('_', '-', $class) . '.php';
        				if(file_exists($file)){
        					require_once($file);
        				}
        				if(!class_exists($class, false)){
        					$class = 'ifwp_error';
        				}
        			}
        		}
        		if(is_callable(array($class, 'get_instance'))){
        			return call_user_func(array($class, 'get_instance'));
        		} else {
                    deactivate_plugins(plugin_basename(IFWP));
            		wp_die('<strong>ERROR</strong>: internal IFWP error.', 'IFWP &rsaquo; error');
                }
        	}
        }
    });

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
