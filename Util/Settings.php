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
		
	$current = ModUtil::getVar($this->name);	
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
	
}