<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Form\Features;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of AddMediaType
 *
 * @author Kaik
 */
class AddMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        //if ($options['upload'] == false) {        	
        $builder->add('files', 'file', [
            'required' => false,
            'attr' => [
                "accept" => $options['allowed_mime_types'],
                "multiple" => "multiple",
            ]        
        ]);
       // }
        
      // var_dump($options);
      // exit(0);
        
        $builder->add('youtube', 'text', [
            'required' => false
        ]);
        
         $builder->add('url', 'text', [
            'required' => false
        ]);          
         
        if ($options['isXmlHttpRequest'] == false) {
          //  $builder->add('save', 'submit', ['label' => 'Save']);
        }                       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'isXmlHttpRequest' => false,
            'allowed_mime_types' => false,
            'upload' => true,
        ]);
    }

    public function getName()
    {
        return 'addmedia_form';
    }
}