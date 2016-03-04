<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Event
 */
class Event
{
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\Sluggable\Sluggable;
    use ORMBehaviors\Translatable\Translatable;
    
    public function __call($method, $args)
    {
        if (!method_exists(self::getTranslationEntityClass(), $method)) {
            $method = 'get' . ucfirst($method);
        }

        return $this->proxyCurrentLocaleTranslation($method, $args);
    }
    
    public function getSluggableFields()
    {
        return [ 'hash','name' ];
    }
    /**
     * @var integer
     */
    private $id;

    /**
     * @var StatusType
     */
    private $status = StatusType::PENDING;

    /**
     * @var string
     */
    private $hash;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $hash = md5(time() + rand(1, 99));
        $this->setHash($hash);        
    }

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
     * Set status
     *
     * @param StatusType $status
     * @return Event
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return StatusType 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Event
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }
    
    /**
     * @var \Krombox\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Krombox\UserBundle\Entity\User $user
     * @return Event
     */
    public function setUser(\Krombox\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Krombox\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $places;


    /**
     * Add places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     * @return Event
     */
    public function addPlace(\Krombox\MainBundle\Entity\Place $places)
    {
        $this->places[] = $places;

        return $this;
    }

    /**
     * Remove places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     */
    public function removePlace(\Krombox\MainBundle\Entity\Place $places)
    {
        $this->places->removeElement($places);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaces()
    {
        return $this->places;
    }
    
    public function prepareForCalendar(){                        
        $e['id'] =    $this->getId();
        $e['title'] = $this->getName();
        $e['start'] = $this->getStartDate()->format('Y-m-d') . ' ' . $this->getStartTime()->format('H:i:s');
        
        if($this->getEndDate())
            $e['end'] = $this->getEndDate()->format('Y-m-d') . ' ' . $this->getEndTime()->format('H:i:s');
        
        return $e;
    }
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;


    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }    

    protected $tags;
    
    public function getTags()
    {
        return $this->tags;
    }
    
    public function setTags($tags)
    {
        $this->tags = $tags;;
    }

    

    /**
     * Add tags
     *
     * @param \Krombox\MainBundle\Entity\MyTag $tags
     * @return Event
     */
    public function addTag(\Krombox\MainBundle\Entity\MyTag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Krombox\MainBundle\Entity\MyTag $tags
     */
    public function removeTag(\Krombox\MainBundle\Entity\MyTag $tags)
    {
        $this->tags->removeElement($tags);
    }
    
    public function getOwner(){
        return $this->getUser();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;


    
   
    /**
     * @var File $image
     */
    protected $image;

 
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
     * @var string
     */
    private $file_name;


    /**
     * Set file_name
     *
     * @param string $fileName
     * @return Event
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->file_name;
    }
    
    /**
     * @var \Krombox\MainBundle\Entity\Place
     */
    private $place;


    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return Event
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
    
    /*VALIDATION*/
    
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        return true;
        //TODO REPLACE TO VALIDATOR
        if(!$this->getAddress() && !$this->getPlace()){                        
            $context->buildViolation('Please, choose location adress or linked place')
                ->atPath('map')                
                ->addViolation();            
        }
    }
    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var \DateTime
     */
    private $startTime;


    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Event
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }
    /**
     * @var \DateTime
     */
    private $endTime;


    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Event
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @var float
     */
    private $priceLow = 0;  

    /**
     * Set priceLow
     *
     * @param float $priceLow
     * @return Event
     */
    public function setPriceLow($priceLow)
    {
        $this->priceLow = $priceLow;

        return $this;
    }

    /**
     * Get priceLow
     *
     * @return float 
     */
    public function getPriceLow()
    {
        return $this->priceLow;
    }
    
    /**
     * @var float
     */
    private $priceHigh = 0;


    /**
     * Set priceHigh
     *
     * @param float $priceHigh
     * @return Event
     */
    public function setPriceHigh($priceHigh)
    {
        $this->priceHigh = $priceHigh;

        return $this;
    }

    /**
     * Get priceHigh
     *
     * @return float 
     */
    public function getPriceHigh()
    {
        return $this->priceHigh;
    }   
    /**
     * @var \Krombox\MainBundle\Entity\EventAddress
     */
    private $address;


    /**
     * Set address
     *
     * @param \Krombox\MainBundle\Entity\EventAddress $address
     * @return Event
     */
    public function setAddress(\Krombox\MainBundle\Entity\EventAddress $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Krombox\MainBundle\Entity\EventAddress 
     */
    public function getAddress()
    {
        return $this->address;
    }
}
