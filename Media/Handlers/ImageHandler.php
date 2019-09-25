<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Media\Handlers;

use Kaikmedia\GalleryModule\Settings\SettingsManager;
use Liip\ImagineBundle\Controller\ImagineController;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
//use Liip\ImagineBundle\Imagine\Filter\;
use Liip\ImagineBundle\Imagine\Filter\FilterConfiguration;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Zikula\Common\Translator\TranslatorInterface;

/**
 * Description of ImageHandler
 *
 * @author Kaik
 */
class ImageHandler extends AbstractMediaHandler 
{
    /**
     * @var TranslatorInterface
     */
    private $translator;    
    
    /**
     * @var EngineInterface
     */
    protected $renderEngine;
    
    /**
     * @var DataManager
     */
    private $dataManager;

    /**
     * @var EngineInterface
     */
    protected $liip_imagine_filter_service;
    
    
    /**
     * @var EngineInterface
     */
    protected $liip_imagine_controller;
    
    /**
     * @var EngineInterface
     */
    protected $liip_cache_manager;
    
    /**
     * @var EngineInterface
     */
    protected $liip_filter_configuration;

    /**
     * @var SettingsManager
     */
    private $settingsManager;
    
    private $mimeTypes = [
        'image/gif' => ['handler' => 'image', 'icon' => 'fa fa-picture-o', 'name' => 'image_gif'],
        'image/jpeg' => ['handler' => 'image', 'icon' => 'fa fa-picture-o', 'name' => 'image_jpeg'],
        'image/png' => ['handler' => 'image', 'icon' => 'fa fa-picture-o', 'name' => 'image_png'],
    ];
    
    /**
     * Construct the handler
     *
     * @param TranslatorInterface $translator
     * @param EngineInterface $renderEngine
     * @param ImagineController $liip_imagine_controller
     * @param CacheManager $liip_cache_manager
     * @param FilterConfiguration $liip_filter_configuration
     * @param SettingsManager $settingsManager
     */
    public function __construct(
        TranslatorInterface $translator,
        EngineInterface $renderEngine,
        DataManager $dataManager,
        ImagineController $liip_imagine_controller,
//        ImagineController $liip_imagine_controller,
        CacheManager $liip_cache_manager,
        FilterConfiguration $liip_filter_configuration,
        SettingsManager $settingsManager
    ) {
        parent::__construct();
        
        $this->translator = $translator;
        $this->renderEngine = $renderEngine;
        $this->dataManager = $dataManager;
        $this->liip_imagine_controller = $liip_imagine_controller;
        $this->liip_cache_manager = $liip_cache_manager;
        $this->liip_filter_configuration = $liip_filter_configuration;
        $this->settingsManager = $settingsManager;
    }
    
    public function getSupportedMimeTypes() 
    {
        return $this->mimeTypes;
    }

    public function getMimeTypeData($mimeType) 
    {
        return array_key_exists($mimeType, $this->mimeTypes) ? $this->mimeTypes[$mimeType] : [];
    }    

    public function getMimeTypeIcon($mimeType) 
    {
        return array_key_exists($mimeType, $this->mimeTypes) ? $this->mimeTypes[$mimeType]['icon'] : '';
    }

    public function isFile() 
    {
        return true;
    }

    public function isText() 
    {
        return false;
    }
    
    public function getTitle($multiple = false) 
    {
        return $multiple ? $this->translator->__('Add images') : $this->translator->__('Add image');
    }
    
    public function getIcon() 
    {
        return 'fa fa-picture-o';
    }    

    public function getPreviewHtml($settings) 
    {
        return 'fa fa-file-image-o';
    }    
    /*    
    array:3 [▼
      "mediaExtra" => array:3 [▼
        "title" => "Screenshot from 2019-03-21 21-15-14.jpg"
        "description" => "baba jaga"
        "legal" => "tak to ona babajaka"
      ]
      "relationExtra" => array:1 [▼
        "display" => "top"
        "filters" =>
            "thumbnail" =>
                "size"          => 
                    "width"     => "1200"
                    "height"    => "675"
                "mode"          => 'inset'
                "allow_upscale" => false
            "background" => 
      ]
      "settings" => array:10 [▼
        "feature" => "featured"
        "css_class" => "main-media-preview"
     * 
     * 
        "filters" =>
            "thumbnail" =>
                "size"          => 
                    "width"     => "1200"
                    "height"    => "675"
                "mode"          => 'inset'
                "allow_upscale" => false
     * "background" => 
     
     
     
     
     * 
        "mode" => "0"
        "autoplay" => "0"
        "show_title" => "0"
        "show_description" => "0"
        "show_legal" => "0"
        "mimeTypes" => array:4 [▶]
      ]
    ]
      */      
    public function getPreviewForDisplay($media, $options) 
    {
        $mediaExtra = $media->getMediaExtra();
        $filterName = 'dynamic';
        // relative to ../web
        $storageRoot = '/uploads'; // $this->settingsManager->getUploadDir(); web/uploads
        $path = $storageRoot . '/'. $mediaExtra['dir'] .'/'. $mediaExtra['savedAs'];
        
        $mode = (array_key_exists('settings', $options) && array_key_exists('mode', $options['settings'])) ? $options['settings']['mode'] : 0;
        
        $this->updateFiltersConfiguration($options, $filterName);
        
        $sourcePath = $this->getSourcePath($path, $filterName, true);
        if (!is_string($sourcePath)) {
            // error handling and default sourcepath
            $sourcePath = '';
        }

        if ($mode == 0) {
            $content = $this->renderEngine->render('KaikmediaGalleryModule:Handler/imageHandler:item.image.html.twig', [
                'media'         => $media,
                'resourcePath'  => $sourcePath,
                'options'       => $options,
            ]);
        } else {
            $content = $sourcePath;
        }
        
      return $content;
    }     
    
    public function getPreviewForEdit($media, $options) 
    {
        $mediaExtra = $media->getMediaExtra();
        $filterName = 'dynamic';
        // relative to ../web
        $storageRoot = '/uploads'; // $this->settingsManager->getUploadDir(); web/uploads
        $path = $storageRoot . '/'. $mediaExtra['dir'] .'/'. $mediaExtra['savedAs'];
        
        $mode = 0;
        
        $this->updateFiltersConfiguration($options, $filterName);
        
        $sourcePath = $this->getSourcePath($path, $filterName, true);
        if (!is_string($sourcePath)) {
            // error handling and default sourcepath
            $sourcePath = '';
        }

        if ($mode == 0) {
            $content = $this->renderEngine->render('KaikmediaGalleryModule:Handler/imageHandler:item.image.html.twig', [
                'media'         => $media,
                'resourcePath'  => $sourcePath,
                'options'       => $options,
            ]);
        } else {
            $content = $sourcePath;
        }
        
      return $content;
    } 

    public function getSourcePath($path, $filterName = 'dynamic', $clearCache = false) 
    {
        try {
            if ($clearCache) {
                $this->liip_cache_manager->remove($path, $filterName);
            }

            /** @var RedirectResponse */
            $response = $this->liip_imagine_controller
                ->filterAction(
                    new Request(),
                    $path,
                    $filterName
            );
            
            $sourcePath = $this->liip_cache_manager
                ->getBrowserPath(
                    $path,
                    $filterName
            );
            
            return $sourcePath;
            
        } catch(\Exception $e) {
            // @todo file missing event ?
            return $e;
        }
    }
        
    public function updateFiltersConfiguration($presets, $filterName = 'dynamic') 
    {
        # Get the filter settings
        $configuration = $this->liip_filter_configuration->get($filterName);
        
        if (array_key_exists('settings', $presets)) {
            $configuration = $this->setFiltersPresets($configuration, $presets['settings']);
        }
        
        if (array_key_exists('relationExtra', $presets)) {
            $configuration = $this->setFiltersPresets($configuration, $presets['relationExtra']);
        }

        dump($presets);
        dump($configuration);
        
        # Set the filter settings
        $this->liip_filter_configuration->set($filterName, $configuration); 
        
        return true;
    }
    
    /*  
        Size Filters
            Thumbnails      1
                # set the thumbnail size to "32x32" pixels
                size: [32, 32]
                # crop the input image, if required
                mode: outbound
            Fixed size      0
                # set the fixed size to "120x90" pixels
                width: 120
                height: 90
            Cropping Images 1
                # set the size of the cropping area
                size: [ 300, 600 ]
                # set the starting coordinates of the crop
                start: [ 040, 160 ]
            Relative Resize 0
            Scale           0
            Down Scale      0
            Up Scale        0
        Orientation Filters
            Auto Rotate     0
                auto_rotate: ~
            Rotate          0
                # set the degree to rotate the image
                angle: 90
            Flip            0
                # set the axis to flip on
                axis: x
        General Filters
            Background      1
                # set the background color to #00ffff
                color: '#00ffff'
                # set a size different from the input image
                size: [1026, 684]
                # center input image on the newly created background
                position: center
            Grayscale       0
                grayscale: ~
            Interlace       0
                # set the interlace mode to none, line, plane, and partition
                mode: line
            Resample        0
                # set the unit to use for pixel density
                unit: ppi
                # set the horizontal pixel density
                x: 72
                # set the vertical pixel density
                y: 72
                # set the resampling filter
                filter: lanczos
                # set the temporary path to use for resampling work
                tmp_dir: /my/custom/temporary/directory/path
            Strip           0
                strip: ~
            Watermark       0
                # path to the watermark file (prepended with "%kernel.root_dir%")
                image: Resources/data/watermark.png
                # size of the water mark relative to the input image
                size: 0.5
                # set the position of the watermark
                position: center 
    */
    public function setFiltersPresets($configuration, $presets)
    {
        # Update filter settings
        // old @deprecated
        #size
        if (array_key_exists('width', $presets) && array_key_exists('height', $presets)) {
            $configuration['filters']['thumbnail']['size'] = [$presets['width'], $presets['height']];
            $configuration['filters']['thumbnail']['mode'] = array_key_exists('mode', $presets) && $presets['mode'] == 'inset' ? 'inset': 'outbound';
        }        
        
        if (array_key_exists('thumbnail', $presets)) {
            $configuration['filters']['thumbnail'] = $this->getPresetThumbnailFilter($presets['thumbnail']);
        }

        if (array_key_exists('crop', $presets)) {
            $configuration['filters']['crop'] = $this->getPresetCropFilter($presets['crop']);
        }
        
        if (array_key_exists('rotate', $presets)) {
            $configuration['filters']['rotate'] = $this->getPresetRotateFilter($presets['rotate']);
        }
        
        if (array_key_exists('background', $presets)) {
            $configuration['filters']['background'] = $this->getPresetBackgroundFilter($presets['background']);
        }
        
        if (array_key_exists('grayscale', $presets) && $presets['grayscale'] == true) {
            $configuration['filters']['grayscale'] = [];
        }
        
        if (array_key_exists('strip', $presets) && $presets['strip'] == true) {
            $configuration['filters']['strip'] = '~';
        }

        return $configuration;
    }
    
    public function getPresetThumbnailFilter($preset) 
    {
        #size
        $thumbnail = false; // default
        if (array_key_exists('size', $preset)) {
            $width = array_key_exists('width', $preset['size']) ? $preset['size']['width'] : 100 ;
            $height = array_key_exists('height', $preset['size']) ? $preset['size']['height'] : 100;    
            #size
            $thumbnail['size'] = [$width, $height];
        }
        #mode
        $thumbnail['mode'] = array_key_exists('mode', $preset) && $preset['mode'] == 'inset' ? 'inset' : 'outbound' ;
        #allow_upscale
        $thumbnail['allow_upscale'] = array_key_exists('allow_upscale', $preset) ? $preset['allow_upscale'] : false;

        return $thumbnail;
    }
    
    public function getPresetCropFilter($preset) 
    {
        #crop
        $crop = false; // default
        if (array_key_exists('size', $preset)) {
            $width = array_key_exists('width', $preset['size']) ? $preset['size']['width'] : 100 ;
            $height = array_key_exists('height', $preset['size']) ? $preset['size']['height'] : 100;    
            #size
            $crop['size'] = [$width, $height];
        }
        if (array_key_exists('start', $preset)) {
            $top = array_key_exists('top', $preset['start']) ? $preset['start']['top'] : 0 ;
            $left = array_key_exists('left', $preset['start']) ? $preset['start']['left'] : 0;    
            #start
            $crop['start'] = [$top, $left];
        }
        
        return $crop;
    }
    
    public function getPresetRotateFilter($preset) 
    {
        return ['axis' => array_key_exists('axis', $preset) ? $preset['axis'] : 0 ];
    }
    
    public function getPresetBackgroundFilter($preset) 
    {
        #background
        $background = false;
        if (array_key_exists('size', $preset)) {
            $width = array_key_exists('width', $preset['size']) ? $preset['size']['width'] : 100 ;
            $height = array_key_exists('height', $preset['size']) ? $preset['size']['height'] : 100;    
            #size
            $background['size'] = [$width, $height];
        }

        #color
        $background['color'] = array_key_exists('color', $preset) ? $preset['color'] : '#fff' ;
        #position
        $background['position'] = array_key_exists('position', $preset) ? $preset['position'] : 'center';
        
        return $background;
    }
}
