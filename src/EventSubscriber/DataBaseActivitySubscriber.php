<?php

namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Film;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;


#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::postRemove)]
class DataBaseActivitySubscriber
{

    /* kernelInterface $appKernel */
    private $appKernel;
    private $rootDir;

    public function __construct(kernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
        $this->rootDir = $appKernel->getProjectDir();
    }


    /**
     * on recupere l'events
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        // on intercepte les evenement de supression
        return [
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    /**
     * Methode de suppresion
     *
     * @param PostRemoveEventArgs $args
     * @return void
     */
    public function postRemove(PostRemoveEventArgs $args): void
    {

        $this->logActivity('remove', $args->getObject());
    }

    /**
     * Methode de mise a jour suit suppresion
     *
     * @param PostUpdateEventArgs $args
     * @return void
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->logActivity('update', $args->getObject());
    }


    /**
     * methode d'action pour la gestion des film et category
     *
     * @param string $action
     * @param mixed $entity
     * @return void
     */
    public function logActivity(string $action, mixed $entity): void
    {


        if (($entity instanceof Film) && $action === "remove") {
            // remove image Product
            $imageUrls = $entity->getImageUrls();
            foreach ($imageUrls as $imageUrl) {
                $filelink = $this->rootDir . "/public/assets/images/films/" . $imageUrl;
                // dd($filelink);
                // Permet de suprimer les images lie a cette table Product
                $this->deleteImage($filelink);
            }
        }

        if (($entity instanceof Category) && $action === "remove") {
            // remove image Category
            $filename = $entity->getImageUrl();
            $filelink = $this->rootDir . "/public/assets/images/categories/" . $filename;

            // Permet de suprimer les images lie a cette table Category
            $this->deleteImage($filelink);
        }
    }


    /**
     * Methode de suppresion d'une image 
     *
     * @param string $filelink
     * @return void
     */
    public function deleteImage(string $filelink): void
    {
        try {
            $result = unlink($filelink);
        } catch (\Throwable $th) {
        }
    }
}
