<?php

namespace Krombox\MainBundle\Entity\Traits;


trait DayFlaggableEntity
{
    /**
     * @\Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $daySunday;
    /**
     * @\Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $dayMonday;
    /**
     * @\Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $dayTuesday;
    /**
     * @\Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $dayWednesday;
    /**
     * @\Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $dayThursday;
    /**
     * @\Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $dayFriday;
    /**
     * @\Doctrine\ORM\Mapping\Column(type="boolean")
     */
    private $daySaturday;

    /**
     * Set daySunday
     *
     * @param boolean $daySunday
     * @return self
     */
    public function setDaySunday($daySunday)
    {
        $this->daySunday = $daySunday;
        return $this;
    }

    /**
     * Get daySunday
     *
     * @return boolean $daySunday
     */
    public function getDaySunday()
    {
        return $this->daySunday;
    }

    /**
     * Set dayMonday
     *
     * @param boolean $dayMonday
     * @return self
     */
    public function setDayMonday($dayMonday)
    {
        $this->dayMonday = $dayMonday;
        return $this;
    }
    /**
     * Get dayMonday
     *
     * @return boolean $dayMonday
     */
    public function getDayMonday()
    {
        return $this->dayMonday;
    }

    /**
     * Set dayTuesday
     *
     * @param boolean $dayTuesday
     * @return self
     */
    public function setDayTuesday($dayTuesday)
    {
        $this->dayTuesday = $dayTuesday;
        return $this;
    }

    /**
     * Get dayTuesday
     *
     * @return boolean $dayTuesday
     */
    public function getDayTuesday()
    {
        return $this->dayTuesday;
    }
    /**
     * Set dayWednesday
     *
     * @param boolean $dayWednesday
     * @return self
     */
    public function setDayWednesday($dayWednesday)
    {
        $this->dayWednesday = $dayWednesday;
        return $this;
    }

    /**
     * Get dayWednesday
     *
     * @return boolean $dayWednesday
     */
    public function getDayWednesday()
    {
        return $this->dayWednesday;
    }

    /**
     * Set dayThursday
     *
     * @param boolean $dayThursday
     * @return self
     */
    public function setDayThursday($dayThursday)
    {
        $this->dayThursday = $dayThursday;
        return $this;
    }

    /**
     * Get dayThursday
     *
     * @return boolean $dayThursday
     */
    public function getDayThursday()
    {
        return $this->dayThursday;
    }

    /**
     * Set dayFriday
     *
     * @param boolean $dayFriday
     * @return self
     */
    public function setDayFriday($dayFriday)
    {
        $this->dayFriday = $dayFriday;
        return $this;
    }

    /**
     * Get dayFriday
     *
     * @return boolean $dayFriday
     */
    public function getDayFriday()
    {
        return $this->dayFriday;
    }

    /**
     * Set daySaturday
     *
     * @param boolean $daySaturday
     * @return self
     */
    public function setDaySaturday($daySaturday)
    {
        $this->daySaturday = $daySaturday;
        return $this;
    }

    /**
     * Get daySaturday
     *
     * @return boolean $daySaturday
     */
    public function getDaySaturday()
    {
        return $this->daySaturday;
    }
    public function getCheckedDays(){
        $ret = [];
        foreach(\Sittr\Common\Functions::getDays() as $d){
            $field = 'day'.ucwords($d);
            if($this->$field){
                $ret[] = substr($d,0,3);
            }
        }
        return $ret;
    }
}
