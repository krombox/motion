<?php

namespace Krombox\MainBundle\Entity;

use Krombox\MainBundle\Entity\Traits\ImageUploadableEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * PlaceImage
 */
class PlaceImage implements ImageUploadableInterface
{
    
    use ImageUploadableEntity;
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var File $back_image
     */
    protected $image;   

    /**
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }    

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return PlaceImage
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }   
    
    public function setImage(File $image = null)
    {
        $this->image = $image;
        
        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @var \Krombox\MainBundle\Entity\Place
     */
    private $place;


    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return PlaceImage
     */
    public function setPlace(\Krombox\MainBundle\Entity\Place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \Krombox\MainBundle\Entity\Place 
     */
    public function getPlace()
    {
        return $this->place;
    }
}
