<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Type\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Kaikmedia\GalleryModule\Collector\MediaHandlersCollector;

use Kaikmedia\GalleryModule\Form\Type\Settings\MimeTypeSettingsType;
use Kaikmedia\GalleryModule\Settings\MimeTypeSettings;

class MimeTypeSelectType extends AbstractType
{
    /**
     * @var MediaHandlersCollector
     */
    private $mediaHandlersCollector;

//    private $mimeTypes;
//    private $mimeTypesSettingsCollection;

    public function __construct(MediaHandlersCollector $mediaHandlersCollector)
    {
        $this->mediaHandlersCollector = $mediaHandlersCollector;
        $this->settingsObj = $this->mediaHandlersCollector->getMimeTypesSettingsObjects();
//        dump($this->mediaHandlersCollector->getMimeTypesSettingsObjects());
//        $this->mimeTypes = $this->mediaHandlersCollector->getSupportedMimeTypesArray();
//        foreach ($this->mimeTypes as $key => $mimeType) {
////            $obj = new MimeTypeSettings();
////            $obj->setMimeType($key);
////            $obj->setHandler($mimeType['handler']);
////            $obj->setName($mimeType['name']);
////            $obj->setMimeType($key);
//            
//            $mm = $mimeType;
//            $mm['mimeType'] = $key;
//            
//            
//            $this->mimeTypesSettingsCollection[] = $mimeType;
//        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_add'] && $options['prototype']) {
            $prototypeOptions = array_replace(array(
                'required' => $options['required'],
                'label' => $options['prototype_name'].'label__',
            ), $options['entry_options']);

            if (null !== $options['prototype_data']) {
                $prototypeOptions['data'] = $options['prototype_data'];
            }

            $prototype = $builder->create($options['prototype_name'], $options['entry_type'], $prototypeOptions);
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        $resizeListener = new ResizeFormListener(
            $options['entry_type'],
            $options['entry_options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty']
        );

        $builder->addEventSubscriber($resizeListener);
        
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                [$this, 'preSetData']
            );
        
    }
    
    public function preSetData(FormEvent $event)
    {
//        $form = $event->getForm();
//        $data = $event->getData();
//
//        if (null === $data) {
//            $data = [];
//        }
//       
////        $form->setData([]);
////        dump($mimeTypesSettingsCollection);
//        
////        $form->add($mimeType['name'], MimeTypeSettingsType::class);
////        $event->setData($mimeTypesSettingsCollection);
//        // @todo maybe some data check?
////        $this->
////        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
////            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
////        }
////        $form->get('children');
////        if ($data->getForm()) {
////            $this->options['matchedEvents'] = $data->getMatchedEvents();
////            dump($data);
//            foreach ($this->mimeTypesSettingsCollection as $mimeT) {
//                
//                
//                
//            }
//
//            
////            dump($this->options);
////            $form->add('settings', $data->getForm(), array_replace(['property_path' => '[settings]'], $this->options));
////            $form->add('matchedEvents', $data->getForm(), array_replace(['property_path' => '[matchedEvents]'], $this->options));
////        }
    }

    public function onSubmit(FormEvent $event)
    {
//        // @todo finish to full sf forms
//        $form = $event->getForm();
//        $data = $event->getData();
//        // At this point, $data is an array or an array-like object that already contains the
//        // new entries, which were added by the data mapper. The data mapper ignores existing
//        // entries, so we need to manually unset removed entries in the collection.
//        if (null === $data) {
//            $data = [];
//        }
//
//        $event->setData($data);
    }
    
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getName()
    {
        return 'kaikmedia_gallery_module_mimetype_select_type';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $entryOptionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';
            return $value;
        };
        $resolver->setDefaults([
            'allow_add' => false,
            'allow_delete' => false,
            'prototype' => true,
            'prototype_data' => null,
            'prototype_name' => '__name__',
            'entry_type' => MimeTypeSettingsType::class,
            'entry_options' => [],
            'delete_empty' => false,
//            'data' => $this->settingsObj
        ]);
        $resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
        $resolver->setAllowedTypes('delete_empty', ['bool', 'callable']);
    }
}
