<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\GalleryModule\Util;

use ServiceUtil;
use UserUtil;
use ModUtil;
use HookUtil;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;

class Settings
{
	public $name = 'KaikmediaGalleryModule';
	
	private $objects;
	private $current;
	private $settings;
	private $features;
	
	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->current = ModUtil::getVar($this->name);
		$this->features = array('gallery','icon','featured','additional','upload','user','public','album','insert');
		$this->_objects();
		$this->previews = array(
				'small' => array('name'=> __('Miniature'),'width'=> '150', 'height'=> '150'),
				'medium' => array('name'=> __('Medium size'),'width'=> '800', 'height'=> '600'),
				'large' => array('name'=> __('Large size'),'width'=> '1920', 'height'=> '1080'),
				'full' => array('name'=> __('Full size'),'width'=> '0', 'height'=> '0')				
		);
		
 		$this->_mergeSettings();		
	}			
	
	/*
	 * Used as ModUtil::getVars()
	 * 
	 */
	public function getSettings()
	{	
		return $this->settings;
	}
	
	/*
	 * Features list
	 * 
	 */	
	public function getFeatures()
	{
		return $this->features;
	}	
	
	/*
	 * Previews list
	 *
	 */	
	public function getPreviews()
	{
		return $this->previews;
	}
	
	/*
	 * Previews list
	 *
	 */
	public function getPreviewData($preview_name)
	{
		return $this->previews[$preview_name];
	}
	
	/*
	 * Previews list
	 *
	 */	
	public function getPreviewsSelect()
	{

		$previews = array();
		foreach($this->previews as $name => $preview){
			$previews[$name] = $preview['name'] . ' ' . $preview['width'] . ' x ' . $preview['height'];				
		}		
		
		return $previews;		
	}	
	
	/*
	 *
	 *
	 */
	public function getObjects()
	{
		return $this->objects;
	}	
	
	/*
	 * 
	 *
	 */
	private function _objects()
	{
	
		$hookSubscribers = HookUtil::getHookSubscribers();
		$obj_list = array();
		foreach ($hookSubscribers as $obj) {
			$obj_list[$obj['name']] = $obj['displayname'];
		}
		//this one always on top
		$obj_list = array('KaikmediaGalleryModule' => $obj_list['KaikmediaGalleryModule']) + $obj_list;
	
		//var_dump($obj_list);
		//exit(0);
	
		$this->objects = $obj_list;
	}	
	
	
	private function _mergeSettings()
	{
		
		$objects = $this->objects;		
		$features = $this->features;
		
		foreach($objects as $object_name => $obj_display_name){
			foreach($features as $feature_name){			
			$done_settings[$object_name][$feature_name] = $this->getFeatureSettings($feature_name, $object_name);
			}			
		}	
				
		$this->settings = $done_settings; 
		
	}
		
	
	public function getFeatureSettings($feature_name, $object_name)
	{	
		
		$feature_default = $this->getFeatureDefault($feature_name);
		$feature_settings = array();
		foreach($feature_default as $setting_name => $setting_value){
			$feature_settings[$setting_name] = !isset($this->current[$object_name][$feature_name][$setting_name]) ? $setting_value : $this->current[$object_name][$feature_name][$setting_name];
			/*
			switch ($feature_setting_name) {
				case 'enabled':
					//default on of setting
					$done_settings[$module][$feature_name][$feature_setting_name] = $_default[$feature_name][$feature_setting_name] == "0" ? $_default[$feature_name][$feature_setting_name] : $feature_setting_value;
					break;
				case 'dir':
					// this setting is not supported per module yet @todo
					$feature_settings[$setting_name] = $setting_value;
					break;
			
				default:
					$feature_settings[$setting_name] = !isset($this->current[$object_name][$feature_name][$setting_name]) ? $setting_value : $this->current[$object_name][$feature_name][$setting_name];
					break;
			}			
			*/
		}
	
		return $feature_settings;		
	}
	
	
	public function getFeatureDefault($feature)
	{
		//@todo add default array against $current chceck
		//add fix func
		/*
		 $dataFix = array();
	
		 foreach($data as $module => $features){
	
		 $dataFix[$module] = $this->getDefault();
	
		 }
		 */
		
		/*
		 foreach($feature_settings as $feature_setting_name => $feature_setting_value){
		 switch ($feature_setting_name) {
		 case 'enabled':
		 //default on of setting
		 $done_settings[$module][$feature_name][$feature_setting_name] = $_default[$feature_name][$feature_setting_name] == "0" ? $_default[$feature_name][$feature_setting_name] : $feature_setting_value;
		 break;
		 case 'dir':
		 // this setting is not supported per module yet @todo
		 $done_settings[$module][$feature_name][$feature_setting_name] = $_default[$feature_name][$feature_setting_name];
		 break;
		 	
		 default:
		 $done_settings[$module][$feature_name][$feature_setting_name] = $feature_setting_value == "" ? $_default[$feature_name][$feature_setting_name] : $feature_setting_value;
		 break;
		 }
		 	
		
		 }*/		
		
		//return $current;
	
		// this array represents default / gallery settings
		$default = array (	

				/*
				 * General gallery defaults per module/obj settings
				 */
				'gallery' => array ( 'enabled' => '1', // gallery is enabled for this module
				),				
				
			//Features settings default
			/* feature icon defaults */
			'icon' =>
			array (
					'enabled' => '1', // feature is enabled in this module
					//display
					'width' => '100', // icon width
					'height' => '100', // icon height single preview
					'perpage' => '', //
					//upload
					'extensions' => 'png,jpg', //
					'mimetypes' => 'image',  //
					//select
					'maxitems' => '', //
					'fields' => 'name,description,alt', //
			),
			/* feature featured defaults */
			'featured' =>
			array (
					'enabled' => '1',
					//display
					'width' => '1000',
					'height' => '1000',
					'perpage' => '',
					//upload
					'extensions' => 'png,jpg',
					'mimetypes' => 'image',
					//select
					'maxitems' => '',
					'fields' => 'name,description,alt,preview', //
			),
			/* feature additional defaults */
			'additional' =>
			array (
					'enabled' => '1',
					//display this need to be adopted to previews
					'width' => '',
					'height' => '',
					'perpage' => '40',
					//upload
					'extensions' => 'png,jpg',
					'mimetypes' => 'image',
					//select
					'maxitems' => '10',
					'fields' => 'name,description,alt,preview', //
			),
			/* feature upload this settings are threded as defaults for whole gallery module */
			'upload' =>
			array (
					'enabled' => '1',
					//this dir is only and only for this module @todo no per module dir settings at the moment
					'dir' => 'kmgallery',
					// general
					'singlemax' => '0',//allowed max size for sigle upload 0 default
					//generall allowed extensions for gallery these can be @todo changed
					'extensions' => 'png,jpg',
					'mimetypes' => 'image',
					//do we need this? hmm
					'perpage' => '',
					//max allowed items if its set for galler
					'maxitems' => '10',
			),
			/* feature user gallery defaults */
			'user' =>
			array (
					'enabled' => '1',
					'perpage' => '40',
			),
			/* feature public gallery defaults */
			'public' =>
			array (
					'enabled' => '1',
					'perpage' => '40',
			),
			'insert' =>
			array (
					'enabled' => '1',
					'fields'  => 'name,alt'
			),
			/* feature albums defaults */
			'album' =>
			array (
					'enabled' => '1',
			),
		);
									// false/null
		return ($default[$feature] == '') ? false : $default[$feature];	
	}		
}