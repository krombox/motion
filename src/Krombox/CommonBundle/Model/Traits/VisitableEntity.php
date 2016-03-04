<?php

namespace Krombox\CommonBundle\Model\Traits;

trait VisitableEntity
{    
    /**  @Doctrine\ORM\Mapping\Column(type="integer", nullable=true) */
    protected $viewsCount = 0;   

    /**
     * @return mixed
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * @param mixed $viewsCount
     */
    public function setViewsCount($viewsCount)
    {
        $this->viewsCount = $viewsCount;
    }
    
    public function getVisitableType()
    {
        return strtolower(get_class($this));
    }
    
    public function incrementViewsCount(){
        $viewsCount = $this->getViewsCount();        
        $this->setViewsCount(++$viewsCount);
    }

}
