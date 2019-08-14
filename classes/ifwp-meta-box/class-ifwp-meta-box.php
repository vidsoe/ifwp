<?php

	if(class_exists('IFWP_Meta_Box', false)){
		ifwp('error')->already_exists('IFWP_Meta_Box class');
	} else {

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	class IFWP_Meta_Box {

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

	static private $bootstrap_4_fields = array(), $conditional_logic = false, $enqueue_dashicons = false, $enqueue_underscore = false, $validation = false;

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function init(){
		if(self::$conditional_logic){
			if(!defined('MB_FRONTEND_SUBMISSION_DIR') and defined('MB_USER_PROFILE_DIR')){
				define('MB_FRONTEND_SUBMISSION_DIR', MB_USER_PROFILE_DIR);
			}
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function rwmb_enqueue_scripts(RW_Meta_Box $object){
		if(self::$bootstrap_4_fields){
			$url = ifwp()->includes_url('select2-bootstrap-theme-0.2.0-beta.4/dist/select2-bootstrap.min.css');
			if($url){
				wp_enqueue_style('select2-bootstrap-theme', $url, array('rwmb-select2'), '0.2.0-beta.4');
				$data = 'jQuery(function($){ $.fn.select2.defaults.set(\'theme\', \'bootstrap\'); });';
				wp_add_inline_script('rwmb-select2', $data);
			}
		}
		if(self::$validation){
			if(!empty($object->meta_box['validation'])){
				wp_dequeue_script('rwmb-validate');
				wp_deregister_script('rwmb-validate');
				wp_enqueue_script('rwmb-validate', plugin_dir_url(IFWP) . 'classes/ifwp-meta-box/js/validate.js', array('jquery-validation', 'jquery-validation-additional-methods'), '4.18.4-fixed', true);
				if(is_callable(array('RWMB_Helpers_Field', 'localize_script_once'))){
					RWMB_Helpers_Field::localize_script_once('rwmb-validate', 'rwmbValidate', array(
						'summaryMessage' => esc_html__('Please correct the errors highlighted below and try again.', 'meta-box'),
					));
				} elseif(is_callable(array('RWMB_Helpers_Field', 'localize_script_once'))){
					RWMB_Field::localize_script('rwmb-validate', 'rwmbValidate', array(
						'summaryMessage' => esc_html__('Please correct the errors highlighted below and try again.', 'meta-box'),
					));
				}
			}
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function rwmb_outer_html($outer_html){
		if(self::$bootstrap_4_fields){
			if($outer_html){
				$outer_html = str_replace('class="rwmb-row', 'class="row', $outer_html);
				$outer_html = str_replace('class="rwmb-column rwmb-column-', 'class="col-' . self::$bootstrap_4_fields['column_width'] . '-', $outer_html);
				$html = str_get_html($outer_html);
				foreach($html->find('div.rwmb-field') as $form_group){
					if(!$form_group->hasClass('rwmb-hidden-wrapper')){
						$form_group->addClass('form-group');
						foreach($form_group->find('.rwmb-input, .rwmb-label') as $element){
							$element->addClass('w-100');
						}
						foreach($form_group->find('.rwmb-input input, .rwmb-input select') as $element){
							$element->addClass('mw-100');
						}
						foreach($form_group->find('div.rwmb-input-group') as $input_group){
							$input_group->class = 'input-group';
							foreach($input_group->find('span.rwmb-input-group-prepend') as $input_group_prepend){
								$input_group_prepend->class = 'input-group-text';
								$input_group_prepend->outertext = '<div class="input-group-prepend">' . $input_group_prepend->outertext . '</div>';
							}
							foreach($input_group->find('span.rwmb-input-group-append') as $input_group_append){
								$input_group_append->class = 'input-group-text';
								$input_group_append->outertext = '<div class="input-group-append">' . $input_group_append->outertext . '</div>';
							}
						}
						foreach($form_group->find('input[type=email], input[type=number], input[type=password], input[type=text], input[type=url], textarea') as $input){
							$input->addClass('form-control');
						}
						foreach($form_group->find('input[type=file]') as $input){
							$input->addClass('custom-file-input');
							$input->outertext = '<div class="custom-file">' . $input->outertext . '<label class="custom-file-label text-truncate" for="' . (isset($input->id) ? $input->id : '') . '" data-browse="' . ($input->getAttribute('data-browse') ? $input->getAttribute('data-browse') : 'Browse') . '">' . ($input->getAttribute('data-choose') ? $input->getAttribute('data-choose') : 'Choose file') . '</label></div>';
						}
						foreach($form_group->find('input[type=range]') as $input){
							$input->addClass('custom-range');
							$output = $input->next_sibling();
							if($output){
								$output->addClass('ml-0');
							}
							$parent = $input->parent();
							if($parent){
								$parent->addClass('text-center');
							}
						}
						foreach($form_group->find('ul.rwmb-input-list') as $list){
							$list_outertext = '';
							foreach($list->find('input') as $input){
								$id = uniqid();
								$input->id = $id;
								$input->addClass('custom-control-input');
								$label = trim(str_replace($input->outertext, '', $input->parent()->innertext));
								$inline = ($list->hasClass('rwmb-inline') ? ' custom-control-inline' : '');
								$list_outertext .= '<div class="custom-control custom-' . $input->type . $inline . '">' . $input->outertext . '<label class="custom-control-label" for="' . $id . '">' . $label . '</label></div>';
							}
							$list->outertext = $list_outertext;
						}
						foreach($form_group->find('label.rwmb-switch-label') as $switch){
							$input = $switch->find('input', 0);
							$input->addClass('custom-control-input');
							$description = $switch->next_sibling();
							$description->addClass('ifwp-remove');
							$switch->outertext = '<div class="custom-control custom-switch">' . $input->outertext . '<label class="custom-control-label" for="' . $input->id . '">' . ($description ? $description->innertext : '')  . '</label></div>';
						}
						foreach($form_group->find('p.description') as $description){
							if(!$description->hasClass('ifwp-remove')){
								$description->outertext = '<small class="form-text text-muted">' . $description->innertext . '</small>';
							} else {
								$description->outertext = '';
							}
						}
					}
				}
				$outer_html = $html->save();
			}
		}
		return $outer_html;
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function wp_enqueue_scripts(){
		if(self::$bootstrap_4_fields){
			$data = 'jQuery(function($){ $(\'button.rwmb-button\').each(function(){ if(!$(this).hasClass(\'.btn\')){ $(this).addClass(\'btn btn-' . self::$bootstrap_4_fields['btn_class'] . '\'); } }); });';
			wp_add_inline_script('jquery', $data);
			wp_enqueue_script('bs-custom-file-input', 'https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js', array('jquery'), '1.3.2');
			$data = 'jQuery(function($){ bsCustomFileInput.init(); });';
			wp_add_inline_script('bs-custom-file-input', $data);
		}
		if(self::$enqueue_dashicons){
			wp_enqueue_style('dashicons');
		}
		if(self::$enqueue_underscore){
			wp_enqueue_script('underscore');
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function wp_head(){
		if(self::$bootstrap_4_fields){ ?>
			<style><?php
                if(self::$bootstrap_4_fields['beaver_builder_theme']){ ?>
                    .form-control {
                        font-size: 1rem !important;
                        height: calc(1.5em + .75rem + 2px) !important;
                        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
                    }
                    textarea.form-control {
                        height: auto !important;
                    }<?php
                } ?>
				.rwmb-button {
					margin: 0 !important;
				}
				.rwmb-button.add-clone {
                    margin-left: 15px !important;
                }
                .rwmb-error {
                    background: transparent !important;
                    border: 0 !important;
                    border-radius: 0 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                .form-control.rwmb-error {
                    background: #fff !important;
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") !important;
                    background-position: center right calc(0.375em + 0.1875rem) !important;
                    background-repeat: no-repeat !important;
                    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
                    border: 1px solid #ced4da !important;
                    border-color: #dc3545 !important;
                    border-radius: .25rem !important;
                    padding: .375rem .75rem !important;
                    padding-right: calc(1.5em + 0.75rem) !important;
                }
                .form-control.rwmb-error:focus {
                    border-color: #dc3545 !important;
                    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
                }
                p.rwmb-error {
                    color: #dc3545 !important;
                    display: block !important;
                    font-size: 80% !important;
                    font-weight: 400 !important;
                    margin: .25rem 0 0 !important;
                }
                .rwmb-field .select2-container {
                    min-width: 0 !important;
					width: 100% !important;
                }
                .rwmb-file {
                    margin-bottom: 0 !important;
                }
                .rwmb-form-submit {
                    padding-top: 0 !important;
                }
                .rwmb-uploaded {
                    padding: 0 !important;
                }
                .select2-container--bootstrap .select2-selection--multiple .select2-selection__rendered {
                    max-width: 100% !important;
                }
            </style><?php
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//
	// methods
	//
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function bootstrap_4_fields($atts = array()){
		if(!is_admin()){
			$defaults = array(
				'beaver_builder_theme' => true,
				'btn_class' => 'primary',
				'column_width' => 'md',
			);
			self::$bootstrap_4_fields = shortcode_atts($defaults, $atts);
			if(!has_action('rwmb_enqueue_scripts', array(__CLASS__, 'rwmb_enqueue_scripts'))){
				add_action('rwmb_enqueue_scripts', array(__CLASS__, 'rwmb_enqueue_scripts'));
			}
			if(!has_action('wp_enqueue_scripts', array(__CLASS__, 'wp_enqueue_scripts'))){
				add_action('wp_enqueue_scripts', array(__CLASS__, 'wp_enqueue_scripts'));
			}
			if(!has_action('wp_head', array(__CLASS__, 'wp_head'))){
				add_action('wp_head', array(__CLASS__, 'wp_head'));
			}
            if(ifwp()->_include('simplehtmldom_1_9/simple_html_dom.php')){
                if(!has_filter('rwmb_outer_html', array(__CLASS__, 'rwmb_outer_html'))){
    				add_filter('rwmb_outer_html', array(__CLASS__, 'rwmb_outer_html'));
    			}
            }
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function conditional_logic(){
		self::$conditional_logic = true;
		if(!has_action('init', array(__CLASS__, 'init'))){
			add_action('init', array(__CLASS__, 'init'));
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function enqueue_dashicons(){
		self::$enqueue_dashicons = true;
		if(!has_action('wp_enqueue_scripts', array(__CLASS__, 'wp_enqueue_scripts'))){
			add_action('wp_enqueue_scripts', array(__CLASS__, 'wp_enqueue_scripts'));
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function enqueue_underscore(){
		self::$enqueue_underscore = true;
		if(!has_action('wp_enqueue_scripts', array(__CLASS__, 'wp_enqueue_scripts'))){
			add_action('wp_enqueue_scripts', array(__CLASS__, 'wp_enqueue_scripts'));
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function validation(){
		self::$validation = true;
		if(!has_action('rwmb_enqueue_scripts', array(__CLASS__, 'rwmb_enqueue_scripts'))){
			add_action('rwmb_enqueue_scripts', array(__CLASS__, 'rwmb_enqueue_scripts'));
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
