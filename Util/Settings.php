<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\GalleryModule\Util;

use ServiceUtil;
use UserUtil;
use ModUtil;

/*
 * Settings
 *
 *
 * {#855 ▼
 +"gallery": {#857 ▼
 +"enabled": "0"
 }
 +"album": {#858 ▼
 +"enabled": "0"
 }
 +"icon": {#859 ▼
 +"enabled": "0"
 +"width": ""
 +"height": ""
 }
 +"image": {#860 ▼
 +"enabled": "0"
 +"width": ""
 +"height": ""
 }
 +"media": {#861 ▼
 +"enabled": "0"
 +"width": ""
 +"height": ""
 }
 +"upload": {#862 ▼
 +"enabled": "0"
 +"allowed": ""
 +"total": ""
 }
 +"user": {#863 ▼
 +"enabled": "0"
 +"allowed": ""
 +"total": ""
 }
 }
 *
 *
 */

class Settings
{
	public $name = 'KaikmediaGalleryModule';
	private $settings;

	/**
	 * constructor
	 */
	public function __construct()
	{	
	
 	$this->_mergeSettings();
		
	}	
	
	public function getSettings()
	{	
		return $this->settings;
	}
	
	private function _mergeSettings()
	{
		
	$data = ModUtil::getVar($this->name);	
	/*
	$dataFix = array();
	
	foreach($data as $module => $features){

		$dataFix[$module] = $this->getDefault();
		
	}
	
	*/
	$current = $data;	
	$_default = $current[$this->name];
	unset($current[$this->name]);
	
	$done_settings = array();
	
	foreach($current as $module => $features){
		foreach($features as $feature_name => $feature_settings){	
			foreach($feature_settings as $feature_setting_name => $feature_setting_value){								
				switch ($feature_setting_name) {					
					case 'enabled':
						$done_settings[$module][$feature_name][$feature_setting_name] = $_default[$feature_name][$feature_setting_name] == "0" ? $_default[$feature_name][$feature_setting_name] : $feature_setting_value;
						break;
					case 'dir':
						//only one gallery 
						$done_settings[$module][$feature_name][$feature_setting_name] = $_default[$feature_name][$feature_setting_name];
						break;
					default:
						$done_settings[$module][$feature_name][$feature_setting_name] = $feature_setting_value == "" ? $_default[$feature_name][$feature_setting_name] : $feature_setting_value;	
						break;
				}				
			
			
			}		
		}
	}	
	
	$done_settings = array('KaikmediaGalleryModule' => $_default) + $done_settings;
	
	$this->settings = $done_settings; 
		
	}
	
	public function getDefault()
	{
		
		$default = array (
				'gallery' =>
				array (
						'enabled' => '1',
				),
				'icon' =>
				array (
						'enabled' => '1',
						'width' => '100',
						'height' => '100',
						'extensions' => 'png,jpg',
						'mimetypes' => 'image',
						'perpage' => '',
						'maxitems' => '',
				),
				'featured' =>
				array (
						'enabled' => '1',
						'width' => '1000',
						'height' => '1000',
						'extensions' => 'png,jpg',
						'mimetypes' => 'image',
						'perpage' => '',
						'maxitems' => '',
				),
				'additional' =>
				array (
						'enabled' => '1',
						'width' => '',
						'height' => '',
						'extensions' => 'png,jpg',
						'mimetypes' => 'image',
						'perpage' => '40',
						'maxitems' => '10',
				),
				'upload' =>
				array (
						'enabled' => '1',
						'dir' => 'kmgallery',
						'singlemax' => '0',
						'extensions' => 'png,jpg',
						'mimetypes' => 'image',
						'perpage' => '',
						'maxitems' => '10',
				),
				'user' =>
				array (
						'enabled' => '1',
						'perpage' => '40',
				),
				'public' =>
				array (
						'enabled' => '1',
						'perpage' => '40',
				),
				'album' =>
				array (
						'enabled' => '1',
				),
		);
	
		return $default;
	}
	
}