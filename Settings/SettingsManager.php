<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Settings;

use Kaikmedia\GalleryModule\Settings\SettingsCollection;
use Zikula\ExtensionsModule\Api\VariableApi;

class SettingsManager {

    private $name = 'KaikmediaGalleryModule';

    private $displayName = 'KMGallery';

    protected $variablesManager;

    private $settings;

    /**
     * construct
     */
    public function __construct(
        VariableApi $variablesManager
    ) {
        $this->settings = new SettingsCollection();
        $this->variablesManager = $variablesManager;
        $dbSettings = $this->variablesManager->getAll($this->name);
        //dump($dbSettings);
        if (is_array($dbSettings) && !empty($dbSettings)) {
            $this->settings->clear();
            $global = $dbSettings[$this->name];
            unset($dbSettings[$this->name]);
            $this->settings->set($this->name, $global);
            foreach($dbSettings as $settingObject){
                $this->settings->set($settingObject->getName(), $settingObject);
            }
        } else {
            //settings init
            $this->settings->addDefault();
        }
    }

    public function setSettings(SettingsCollection $settings)
    {
        if(!$settings->containsKey($this->name)) {
           return false;
        }

        $this->settings->postSubmit($settings);

        return true;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSettingsArray()
    {
        $array = $this->settings->toArray();

        return $array;
    }

    public function getSettingsForForm()
    {
        return ['settings' => $this->settings];
    }

    public function saveSettings()
    {
        return $this->variablesManager->setAll($this->name, $this->settings->toArrayForStorage());
    }
}
