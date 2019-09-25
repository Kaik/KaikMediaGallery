<?php
/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Type;

use Symfony\Component\Form\AbstractType;
use Zikula\SettingsModule\Api\ApiInterface\LocaleApiInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Description of CategoryType
 *
 * @author Kaik
 */
class TranslatableType extends AbstractType
{
    /**
     * @var LocaleApiInterface
     */
    private $localeApi;

    /**
     * TranslationType constructor.
     * @param LocaleApiInterface $localeApi
     */
    public function __construct(LocaleApiInterface $localeApi)
    {
        $this->localeApi = $localeApi;
        $this->locales = $this->localeApi->getSupportedLocaleNames(); 
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        foreach ($this->locales as $title => $locale) {
            $builder->add($locale, TextType::class, [
                    'label' => $title,
                ]
            );
        }
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $supportedLocales = $this->localeApi->getSupportedLocaleNames();
            $data = $event->getData();
            
            if (!is_array($data)) {
                $data = [];
            }
            
            $labels = [];
            foreach ($supportedLocales as $title => $locale) {
                if (array_key_exists($locale, $data)) {
                    $labels[$locale] = $data[$locale];
                } else {
                    $labels[$locale] = '';
                }
            }
            
            $event->setData($labels);
        }); 
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        
    }

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
        return "kmgallery_translatable_type";
    }
}
