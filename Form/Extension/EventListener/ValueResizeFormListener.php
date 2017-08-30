<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Form\Extension\EventListener;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvent;

/**
 * Description of ResizeFormListener
 *
 * @author Kaik
 */
class ValueResizeFormListener extends ResizeFormListener {

    //put your code here

    public function preSetData(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = [];
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        // Then add all rows again in the correct order
        foreach ($data as $name => $value) {
            $class = $value->getFormClass();
            $type = new $class();
            $form->add($name, $type ,array_replace([
                'property_path' => '[' . $name . ']',
                            ], $this->options));
        }
    }

}
