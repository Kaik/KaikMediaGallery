<?php
/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Type\Hook\Providers\GalleryProvider;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Description of CategoryType
 *
 * @author Kaik
 */
class GalleryStyleChooserType extends AbstractType
{
    public function getGalleryStyles()
    {        
//        return [
//           'max' => [
//               'name'   => 'max',
//               'title'  => $this->translator->__('Max'),
//               'icon'   => 'fa fa-square',
//           ],
//           'slider' => [
//               'name'   => 'slider',
//               'title'  => $this->translator->__('Slider'),
//               'icon'   => 'fa fa-indent',
//           ],
//           'tiles' => [
//               'name'   => 'tiles',
//               'title'  => $this->translator->__('Tiles'),
//               'icon'   => 'fa fa-th-small',
//           ],
//           'list' => [
//               'name'   => 'list',
//               'title'  => $this->translator->__('List'),
//               'icon'   => 'fa fa-th-list',
//           ],
//        ];
        
        
        return ['Max'       => 'max', 
                'Slider'    => 'slider',
                'Tiles'     => 'tiles',
                'List'      => 'list',
            ];
    }    
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('style', ChoiceType::class, ['choices' => ['Max'       => 'max', 
                'Slider'    => 'slider',
                'Tiles'     => 'tiles',
                'List'      => 'list',
            ],
                'multiple'  => false,
                'expanded'  => true,
                'required'  => true,
        ]);
        
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $supportedLocales = $this->localeApi->getSupportedLocaleNames();
//            $data = $event->getData();
//            
//            if (!is_array($data)) {
//                $data = [];
//            }
//            
//            $labels = [];
//            foreach ($supportedLocales as $title => $locale) {
//                if (array_key_exists($locale, $data)) {
//                    $labels[$locale] = $data[$locale];
//                } else {
//                    $labels[$locale] = '';
//                }
//            }
//            
//            $event->setData($labels);
//        }); 
    }
    
//    /**
//     * {@inheritdoc}
//     */
//    public function buildView(FormView $view, FormInterface $form, array $options)
//    {
//        
//    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix()
    {
        return "kmgallery_gallery_style_chooser_type";
    }
}
