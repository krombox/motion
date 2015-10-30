<?php

namespace Krombox\CommonBundle\Model\Traits;

use Krombox\CommonBundle\Model\RateableInterface;

trait RateableEntity
{
    /**  @Doctrine\ORM\Mapping\Column(type="decimal", scale=2, nullable=true) */
    protected $rating;
    /**  @Doctrine\ORM\Mapping\Column(type="integer", nullable=true) */
    protected $ratingCount;

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    /**
     * @param mixed $ratingCount
     */
    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;
    }

}
