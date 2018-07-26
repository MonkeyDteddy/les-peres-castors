<?php

namespace PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ImageStory
 *
 * @ORM\Table(name="image_story")
 * @ORM\Entity(repositoryClass="PlatformBundle\Repository\ImageStoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ImageStory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * 
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @Assert\File(mimeTypes={ "image/jpg", "image/jpeg" , "image/png"})
     */
    private $file;

    private $tempFilename;
  
    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if(null !== $this->url)
        {
            $this->tempFilename = $this->url;

            $this->url = null;
            $this->alt = null;
        }
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return ImageStory
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return ImageStory
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        //var_dump($this->file->getClientOriginalName());
        if(null === $this->file)
        {
            $this->url = 'default.jpg';
            $this->alt = 'pere castor';
        }

        
        $this->url = $this->file->getClientOriginalName();
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        
        if(null === $this->file)
        {
            return;
        }

        if(null !== $this->tempFilename) {
            $oldFile = $this->getUploadDir().'/'. $this->id . '.' . $this->tempFilename;
            if(file_exists($oldFile))
            {
                unlink($oldFile);
            }
        }

        
        $this->file->move($this->getUploadRootDir(), $this->id . '.' . $this->url);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempFilename = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->url;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if(file_exists($this->tempFilename)) 
        {
            unlink($this->tempFilename);
        }
    }



    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'img/story';
    }

  protected function getUploadRootDir()
  {
    // On retourne le chemin relatif vers l'image pour notre code PHP
    return __DIR__.'/../../../web/'.$this->getUploadDir();
  }

}
