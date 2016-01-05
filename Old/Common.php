<?php
/**
 * Copyright (c) KaikMedia.com 2014
 */
namespace Kaikmedia\GalleryModule\Util;

class Common
{
    /**
     * Check upload max size defined by php
     * 
     */
    public function getUploadLimit()
    {
        static $max_size = -1;
        
        if ($max_size < 0) {
            // Start with post_max_size.
            $max_size = self::parse_size(ini_get('post_max_size'));
        
            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = self::parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;        

    }  
    
    /**
     * Check user's current usage
     *
     */
    public function getUserTotalUpload()
    {

        $this->entityManager = \ServiceUtil::getService('doctrine.entitymanager');
        // Do a new query in order to limit maxresults, firstresult, order, etc.
        $query = $this->entityManager->createQueryBuilder()
        ->select('SUM(p.size)')
        ->from('Kaikmedia\GalleryModule\Entity\MediaEntity', 'p')
        ->where('p.author = :author')
        ->setParameter('author', \UserUtil::getVar('uid'))
        ->getQuery();          

        return $query->getSingleScalarResult();
    
    }    

    private function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }    
}