<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Krombox\CommonBundle\Model\Traits\RateableEntity;
use Krombox\CommonBundle\Model\Traits\VisitableEntity;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\DBAL\Types\DayType;
use Krombox\MainBundle\DBAL\Types\MembershipType;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;
use Krombox\MainBundle\DBAL\Types\LikeType;
use \DateTime;
use \DateInterval;
use Cocur\Slugify\Slugify;
use Krombox\CommonBundle\Model\RateableInterface;
use Krombox\CommonBundle\Model\VisitableInterface;
use Krombox\MainBundle\Validator\Constraints as MainAssert;
use Symfony\Component\Validator\Constraints as Assert;


class Place implements RateableInterface, VisitableInterface
{    
    use ORMBehaviors\Timestampable\Timestampable;                
    use ORMBehaviors\Translatable\Translatable;
    //use ORMBehaviors\Sluggable\Sluggable;
    use RateableEntity,
        VisitableEntity
    ;
    
    
    public function __call($method, $args)
    {
        if (!method_exists(self::getTranslationEntityClass(), $method)) {
            $method = 'get' . ucfirst($method);
        }

        return $this->proxyCurrentLocaleTranslation($method, $args);
    }
    
//    public function getSluggableFields()
//    {
//        return ['hash','name' ];
//    }

    /**
     * @var integer
     */
    private $id;        


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
     * @var \Krombox\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Krombox\UserBundle\Entity\User $user
     * @return Place
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
    private $placeImages;

    /**
     * Constructor
     */
    public function __construct()
    {                
        /*Changed in postPersist event*/        
        $this->placeImages = new \Doctrine\Common\Collections\ArrayCollection();        
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->halls = new \Doctrine\Common\Collections\ArrayCollection();
        $hash = md5(time() + rand(1, 99));
        $this->setHash($hash);
        $this->rating = 0;
        $this->ratingCount = 0;
        $this->is24h = false;
        $this->status = StatusType::PENDING;
    }

    /**
     * Add placeImages
     *
     * @param \Krombox\MainBundle\Entity\PlaceImage $placeImages
     * @return Place
     */
    public function addPlaceImage(\Krombox\MainBundle\Entity\PlaceImage $placeImages)
    {               
        $this->placeImages[] = $placeImages;
        $placeImages->setPlace($this);
                
        return $this;
    }

    /**
     * Remove placeImages
     *
     * @param \Krombox\MainBundle\Entity\PlaceImage $placeImages
     */
    public function removePlaceImage(\Krombox\MainBundle\Entity\PlaceImage $placeImages)
    {
        $this->placeImages->removeElement($placeImages);
        //$placeImages->setPlace(null);
    }

    /**
     * Get placeImages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceImages()
    {
        return $this->placeImages;
    }    
    /**
     * @var string
     */
    //private $phone;

    /**
     * @var boolean
     */
    private $is24h;

    /**
     * @var string
     */
    private $website;  

    /**
     * Set is24h
     *
     * @param boolean $is24h
     * @return Place
     */
    public function setIs24h($is24h)
    {
        $this->is24h = $is24h;

        return $this;
    }

    /**
     * Get is24h
     *
     * @return boolean 
     */
    public function getIs24h()
    {
        return $this->is24h;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Place
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }    
    
    /**
     * @var StatusType
     */
    private $status;


    /**
     * Set status
     *
     * @param StatusType $status
     * @return Place
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
     * @var \Doctrine\Common\Collections\Collection
     * @Assert\Valid
     */
    private $businessHours;


    /**
     * Add businessHours
     *
     * @param \Krombox\MainBundle\Entity\BusinessHours $businessHours
     * @return Place
     */
    public function addBusinessHour(\Krombox\MainBundle\Entity\BusinessHours $businessHours)
    {
        $businessHours->setPlace($this);
        $this->businessHours[] = $businessHours;        
        
        return $this;
    }

    /**
     * Remove businessHours
     *
     * @param \Krombox\MainBundle\Entity\BusinessHours $businessHours
     */
    public function removeBusinessHour(\Krombox\MainBundle\Entity\BusinessHours $businessHours)
    {
        $this->businessHours->removeElement($businessHours);
    }

    /**
     * Get businessHours
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusinessHours()
    {
        return $this->businessHours;
    }        

    /**
     * @var string
     */
    private $address;


    /**
     * Set address
     *
     * @param string $address
     * @return Place
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }               
    
    public function getCategoriesName(){
        $categories = [];
        foreach($this->getCategories() as $cat){
            $categories[] = $cat->getName();
        }
        
        return $categories;
    }
    
    /**
     * @var string
     */
    private $hash;


    /**
     * Set hash
     *
     * @param string $hash
     * @return Place
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
    
    public function getUserRating($user)
    {        
        foreach($this->getRatings() as $rating){
            if($rating->getUser() == $user)
                return $rating->getRate();
        }
       
        return null;
    }
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $events;


    /**
     * Add events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     * @return Place
     */
    public function addEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events[] = $events;        
        return $this;
    }

    /**
     * Remove events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     */
    public function removeEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }    

    /**
     * Add placesLinked
     *
     * @param \Krombox\MainBundle\Entity\Place $placesLinked
     * @return Place
     */
    public function addPlacesLinked(\Krombox\MainBundle\Entity\Place $placesLinked)
    {
        $this->placesLinked[] = $placesLinked;
        return $this;
    }

    /**
     * Remove placesLinked
     *
     * @param \Krombox\MainBundle\Entity\Place $placesLinked
     */
    public function removePlacesLinked(\Krombox\MainBundle\Entity\Place $placesLinked)
    {
        $this->placesLinked->removeElement($placesLinked);
    }

    /**
     * Get placesLinked
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlacesLinked()
    {
        return $this->placesLinked;
    }
    
    public function getOwner(){
        return $this->getUser();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Assert\Valid
     */
    private $businessHoursException;


    /**
     * Add businessHoursException
     *
     * @param \Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException
     * @return Place
     */
    public function addBusinessHoursException(\Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException)
    {
        $this->businessHoursException[] = $businessHoursException;
        $businessHoursException->setPlace($this);

        return $this;
    }

    /**
     * Remove businessHoursException
     *
     * @param \Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException
     */
    public function removeBusinessHoursException(\Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException)
    {
        $this->businessHoursException->removeElement($businessHoursException);
    }

    /**
     * Get businessHoursException
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusinessHoursException()
    {
        return $this->businessHoursException;
    }
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $phones;


    /**
     * Add phones
     *
     * @param \Krombox\MainBundle\Entity\Phone $phones
     * @return Place
     */
    public function addPhone(\Krombox\MainBundle\Entity\Phone $phones)
    {        
        $phones->setPlace($this);
        $this->phones[] = $phones;                                
        
        return $this;
    }

    /**
     * Remove phones
     *
     * @param \Krombox\MainBundle\Entity\Phone $phones
     */
    public function removePhone(\Krombox\MainBundle\Entity\Phone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }    
            
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $halls;


    /**
     * Add halls
     *
     * @param \Krombox\MainBundle\Entity\Hall $halls
     * @return Place
     */
    public function addHall(\Krombox\MainBundle\Entity\Hall $halls)
    {
        $halls->setPlace($this);
        $this->halls[] = $halls;

        return $this;
    }

    /**
     * Remove halls
     *
     * @param \Krombox\MainBundle\Entity\Hall $halls
     */
    public function removeHall(\Krombox\MainBundle\Entity\Hall $halls)
    {
        $this->halls->removeElement($halls);
    }

    /**
     * Get halls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHalls()
    {
        return $this->halls;
    }                          
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ratings;   

    /**
     * Add ratings
     *
     * @param \Krombox\MainBundle\Entity\Rating $ratings
     * @return Place
     */
    public function addRating(\Krombox\MainBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;

        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \Krombox\MainBundle\Entity\Rating $ratings
     */
    public function removeRating(\Krombox\MainBundle\Entity\Rating $ratings)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }
    
    /**
     * @var \Krombox\MainBundle\Entity\PlaceImage
     */
    private $logo;


    /**
     * Set logo
     *
     * @param \Krombox\MainBundle\Entity\PlaceImage $logo
     * @return Place
     */
    public function setLogo(\Krombox\MainBundle\Entity\PlaceImage $logo = null)
    {
        $this->logo = $logo;
        //$logo->setPlace($this);

        return $this;
    }

    /**
     * Get logo
     *
     * @return \Krombox\MainBundle\Entity\PlaceImage 
     */
    public function getLogo()
    {
        return $this->logo;
    }    

    /**
     * Get membership
     *
     * @return \Krombox\MainBundle\Entity\MembershipSubscription 
     */
    public function getMembership()
    {
        foreach ($this->getMembershipSubscriptions() as $membership){
            if($membership->getStatus() == MembershipStatusType::ACTIVE){
                return $membership->getMembership();
            }
        }
    }
    
    /**
     * Get membershipSubscription
     *
     * @return \Krombox\MainBundle\Entity\MembershipSubscription 
     */
    public function getActiveMembershipSubscription()
    {
        foreach ($this->getMembershipSubscriptions() as $membership){
            if($membership->getStatus() == MembershipStatusType::ACTIVE){
                return $membership;
            }
        }
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $membershipSubscriptions;


    /**
     * Add membershipSubscriptions
     *
     * @param \Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions
     * @return Place
     */
    public function addMembershipSubscription(\Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions)
    {
        $this->membershipSubscriptions[] = $membershipSubscriptions;

        return $this;
    }

    /**
     * Remove membershipSubscriptions
     *
     * @param \Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions
     */
    public function removeMembershipSubscription(\Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions)
    {
        $this->membershipSubscriptions->removeElement($membershipSubscriptions);
    }

    /**
     * Get membershipSubscriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembershipSubscriptions()
    {
        return $this->membershipSubscriptions;
    }    
          
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placeFilterValues;


    /**
     * Add placeFilterValues
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues
     * @return Place
     */
    public function addPlaceFilterValue(\Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues)
    {
        $this->placeFilterValues[] = $placeFilterValues;

        return $this;
    }

    /**
     * Remove placeFilterValues
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues
     */
    public function removePlaceFilterValue(\Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues)
    {
        $this->placeFilterValues->removeElement($placeFilterValues);
    }

    /**
     * Get placeFilterValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceFilterValues()
    {
        return $this->placeFilterValues;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * Add categories
     *
     * @param \Krombox\MainBundle\Entity\Category $categories
     * @return Place
     */
    public function addCategory(\Krombox\MainBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Krombox\MainBundle\Entity\Category $categories
     */
    public function removeCategory(\Krombox\MainBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * @var string
     */
    private $email;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $socialLinks;   

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placesLinked;


    /**
     * Set email
     *
     * @param string $email
     * @return Place
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add socialLinks
     *
     * @param \Krombox\MainBundle\Entity\SocialLink $socialLinks
     * @return Place
     */
    public function addSocialLink(\Krombox\MainBundle\Entity\SocialLink $socialLinks)
    {
        $socialLinks->setPlace($this);
        $this->socialLinks[] = $socialLinks;

        return $this;
    }

    /**
     * Remove socialLinks
     *
     * @param \Krombox\MainBundle\Entity\SocialLink $socialLinks
     */
    public function removeSocialLink(\Krombox\MainBundle\Entity\SocialLink $socialLinks)
    {
        $this->socialLinks->removeElement($socialLinks);
    }

    /**
     * Get socialLinks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSocialLinks()
    {
        return $this->socialLinks;
    }   
    /**
     * @var string
     */
    private $slug;


    /**
     * Set slug
     *
     * @param string $slug
     * @return Place
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function getNameTranslatableRU(){
        return $this->translate('ru')->getName();
    }
    
    public function getNameTranslatableEN(){
        return $this->translate('en')->getName();
    }
    
    public function getVisitableId() {
        return $this->getSlug();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ordersMembership;


    /**
     * Add ordersMembership
     *
     * @param \Krombox\PaymentBundle\Entity\OrderMembership $ordersMembership
     * @return Place
     */
    public function addOrdersMembership(\Krombox\PaymentBundle\Entity\OrderMembership $ordersMembership)
    {
        $this->ordersMembership[] = $ordersMembership;

        return $this;
    }

    /**
     * Remove ordersMembership
     *
     * @param \Krombox\PaymentBundle\Entity\OrderMembership $ordersMembership
     */
    public function removeOrdersMembership(\Krombox\PaymentBundle\Entity\OrderMembership $ordersMembership)
    {
        $this->ordersMembership->removeElement($ordersMembership);
    }

    /**
     * Get ordersMembership
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrdersMembership()
    {
        return $this->ordersMembership;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $city;


    /**
     * Add city
     *
     * @param \Krombox\MainBundle\Entity\City $city
     * @return Place
     */
    public function addCity(\Krombox\MainBundle\Entity\City $city)
    {
        $this->city[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \Krombox\MainBundle\Entity\City $city
     */
    public function removeCity(\Krombox\MainBundle\Entity\City $city)
    {
        $this->city->removeElement($city);
    }

    /**
     * Get city
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param \Krombox\MainBundle\Entity\City $city
     * @return Place
     */
    public function setCity(\Krombox\MainBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }
}
