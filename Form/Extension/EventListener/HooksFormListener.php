<?php

/*
 * KaikMedia GalleryModule
 *
 * @package    KaikmediaGalleryModule
 * @copyright  KaikMedia.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       https://github.com/Kaik/KaikMediaGallery.git
 */

namespace Kaikmedia\GalleryModule\Form\Extension\EventListener;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvent;

/**
 * HooksFormListener
 *
 * @author Kaik
 */
class HooksFormListener extends ResizeFormListener
{
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = [];
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        foreach ($data as $name => $hookBundle) {
            //global hook bundle settings form
            if ($hookBundle->getSettingsForm()) {
                $form->add($name, $hookBundle->getSettingsForm(), array_replace(['property_path' => '[' . $name . ']'], $this->options));
            }
        }
    }
}
