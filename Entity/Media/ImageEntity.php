<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kaikmedia\GalleryModule\Entity\Media;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ImageEntity
 * @ORM\Entity()
 * 
 * @author Kaik
 */
class ImageEntity extends AbstractUploadableEntity {
    //put your code here

    public function newUpload(array $info)
    {
        //parent::newUpload($info);

    }
}