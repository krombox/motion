<?php

namespace Krombox\MainBundle\Entity;

use Krombox\MainBundle\Entity\Traits\ImageUploadableEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * PlaceHallImage
 */
class PlaceHallImage implements ImageUploadableInterface
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
     * @var string
     */
    //private $file_name;

    /**
     * @var \DateTime
     */
    private $updatedAt;  

    /**
     * @var \Krombox\MainBundle\Entity\Hall
     */
    private $hall;


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
     * Set file_name
     *
     * @param string $fileName
     * @return PlaceHallImage
     */
//    public function setFileName($fileName)
//    {
//        $this->file_name = $fileName;
//
//        return $this;
//    }
//
//    /**
//     * Get file_name
//     *
//     * @return string 
//     */
//    public function getFileName()
//    {
//        return $this->file_name;
//    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return PlaceHallImage
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

    /**
     * Set hall
     *
     * @param \Krombox\MainBundle\Entity\Hall $hall
     * @return PlaceHallImage
     */
    public function setHall(\Krombox\MainBundle\Entity\Hall $hall = null)
    {
        $this->hall = $hall;

        return $this;
    }

    /**
     * Get hall
     *
     * @return \Krombox\MainBundle\Entity\Hall 
     */
    public function getHall()
    {
        return $this->hall;
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
}
