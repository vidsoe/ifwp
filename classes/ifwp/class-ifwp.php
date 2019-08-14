<?php

	if(class_exists('IFWP', false)){
		ifwp('error')->already_exists('IFWP class');
	} else {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	class IFWP {

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
	// methods
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function _include($path = ''){
		$path = wp_normalize_path($path);
		if($path and is_string($path)){
			$path = ltrim($path, '/');
			$file = plugin_dir_path(IFWP) . 'includes/' . $path;
			if(file_exists($file)){
				require_once($file);
				return true;
			}
		}
		return false;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function attachment_url_to_postid($url = ''){
		if($url){
			// original
			$post_id = $this->guid_to_postid($url);
			if($post_id){
				return $post_id;
			}
			// resized
			preg_match('/^(.+)(-\d+x\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
			if($matches){
				$url = $matches[1];
				if(isset($matches[3])){
					$url .= $matches[3];
				}
			}
			$post_id = $this->guid_to_postid($url);
			if($post_id){
				return $post_id;
			}
			// edited
			preg_match('/^(.+)(-e\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
			if($matches){
				$url = $matches[1];
				if(isset($matches[3])){
					$url .= $matches[3];
				}
			}
			$post_id = $this->guid_to_postid($url);
			if($post_id){
				return $post_id;
			}
		}
		return 0;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function base64_urldecode($data){
		return base64_decode(strtr($data, '-_', '+/'));
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function base64_urlencode($data){
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function guid_to_postid($guid = ''){
		global $wpdb;
		if($guid){
			$str = "SELECT ID FROM $wpdb->posts WHERE guid = %s";
			$sql = $wpdb->prepare($str, $guid);
			$post_id = $wpdb->get_var($sql);
			if($post_id){
				return (int) $post_id;
			}
		}
		return 0;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function includes_url($path = ''){
		$path = wp_normalize_path($path);
		if($path and is_string($path)){
			$path = ltrim($path, '/');
			$file = plugin_dir_path(IFWP) . 'includes/' . $path;
			if(file_exists($file)){
				return plugin_dir_url(IFWP) . 'includes/' . $path;
			}
		}
		return '';
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function json_decode_response_data($response = array(), $assoc = false, $depth = 512, $options = 0){
		if(is_array($response) and isset($response['data'], $response['success']) and preg_match('/^\{\".*\"\:.*\}$/', trim($response['data']))){
			$data = json_decode(trim($response['data']), $assoc, $depth, $options);
			if(json_last_error() === JSON_ERROR_NONE){
				$response['data'] = $data;
			} else {
				$response['data'] = 'JSON cannot be decoded or the encoded data is deeper than the recursion limit';
				$response['success'] = false;
			}
		}
		return $response;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function parse_remote_response($response = null){
		if(is_wp_error($response)){
			return array(
				'data' => $response->get_error_message(),
				'success' => false,
			);
		}
		$response_code = wp_remote_retrieve_response_code($response);
		if($response_code == 200){
			return array(
				'data' => wp_remote_retrieve_body($response),
				'success' => true,
			);
		}
		$response_message = wp_remote_retrieve_response_message($response);
		if(!$response_message){
			$response_message = get_status_header_desc($response_code);
		}
		if($response_message){
			return array(
				'data' => $response_message,
				'success' => false,
			);
		}
		return array(
			'data' => __('Something went wrong.'),
			'success' => false,
		);
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function parse_requests_response($response = null){
		if(is_a($response, 'Requests_Exception')){
			return array(
				'data' => $response->getMessage(),
				'success' => false,
			);
		}
		if(is_a($response, 'Requests_Response')){
			$response_code = $response->status_code;
			if($response_code == 200){
				return array(
					'data' => $response->body,
					'success' => true,
				);
			}
			$response_message = get_status_header_desc($response_code);
			if($response_message){
				return array(
					'data' => $response_message,
					'success' => false,
				);
			}
		}
		return array(
			'data' => __('Something went wrong.'),
			'success' => false,
		);
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function referer_to_postid(){
		$referer = wp_get_referer();
		if($referer){
			return $this->url_to_postid($referer);
		}
		return 0;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function update(){
		if($this->_include('plugin-update-checker-4.7/plugin-update-checker.php')){
			Puc_v4_Factory::buildUpdateChecker('https://github.com/vidsoe/ifwp', IFWP, 'ifwp');
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	function url_to_postid($url = ''){
		if($url){
			$post_id = $this->url_to_postid($url);
			if($post_id){
				return $post_id;
			}
			$post_id = $this->attachment_url_to_postid($url);
			if($post_id){
				return $post_id;
			}
		}
		return 0;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
