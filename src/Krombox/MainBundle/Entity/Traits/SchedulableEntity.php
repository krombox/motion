<?php

namespace Krombox\MainBundle\Entity\Traits;


trait SchedulableEntity
{
    /**
     * @var string
     * @\Doctrine\ORM\Mapping\Column(type="datetime", nullable=false)
     * @\Symfony\Component\Validator\Constraints\NotBlank()
     */
    private $startsAt;
    /**
     * @var string
     * @\Doctrine\ORM\Mapping\Column(type="datetime", nullable=false)
     * @\Symfony\Component\Validator\Constraints\NotBlank()
     */
    private $endsAt;

    /**
     * @param string $endsAt
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * @param string $startsAt
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * @return \DateInterval
     */
    public function getInterval()
    {
        if($this->getStartsAt() && $this->getEndsAt())
            return date_diff($this->getStartsAt(), $this->getEndsAt());
        else
            return null;
    }


    public function setInterval(\DateInterval $interval = null){
        if($interval){
            $d = clone $this->getStartsAt();
            $d->add($interval);
            $this->setEndsAt($d);       
            return $this;
        }
    }
}
