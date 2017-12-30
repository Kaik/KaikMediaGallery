<?php

// src/Acme/DemoBundle/EventListener/DynamicRelationSubscriber.php
class DynamicRelationSubscriber implements EventSubscriber
{
    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        // the $metadata is the whole mapping info for this class
        $metadata = $eventArgs->getClassMetadata();

        if ($metadata->getName() != 'Kaikmedia\GalleryModule\Entity\PageEntity') {
            return;
        }

        $namingStrategy = $eventArgs
        ->getEntityManager()
        ->getConfiguration()
        ->getNamingStrategy()
        ;

    }
}
