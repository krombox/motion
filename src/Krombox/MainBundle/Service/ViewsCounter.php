<?php

namespace Krombox\MainBundle\Service;

use Krombox\CommonBundle\Model\VisitableInterface;
use Krombox\MainBundle\Service\VisitableManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */

/**
 * @DI\Service("krombox.views_counter")
 */
class ViewsCounter {
    
    const SESSION_ARRAY_VARIABLE = 'unique_views';
    
    private $session;
    private $visitableManager;
    
    /**
     * @DI\InjectParams({
     *  "session" = @DI\Inject("session"),
     *  "visitableManager" = @DI\Inject("krombox.visitable_manager")    
     * })
     */
    public function __construct(SessionInterface $session, VisitableManager $visitableManager)
    {
        $this->session = $session;
        $this->visitableManager = $visitableManager;
    }
    /**
     * {@inheritdoc}
     */
    public function count(VisitableInterface $visitable)
    {
        $uniqueViews = $this->getUniqueViewsVariable();
        
        if ($uniqueViews === null) {
            $uniqueViews = array();
            $this->saveVisitableType($uniqueViews, $visitable);
            $this->visitableManager->update($visitable);
        } elseif (!isset($uniqueViews[$visitable->getVisitableType()])) {
            $this->saveVisitableType($uniqueViews, $visitable);
            $this->visitableManager->update($visitable);
        } elseif (!isset($uniqueViews[$visitable->getVisitableType()][$visitable->getVisitableId()])) {
            $this->saveVisitableId($uniqueViews, $visitable);
            $this->visitableManager->update($visitable);
        }
    }
    
    /**
     * @param array $uniqueViews
     * @param VisitableInterface $visitable
     */
    private function saveVisitableId(array $uniqueViews, VisitableInterface $visitable)
    {
        $uniqueViews[$visitable->getVisitableType()][$visitable->getVisitableId()] = $visitable->getVisitableId();
        $this->session->set(self::SESSION_ARRAY_VARIABLE, $uniqueViews);
    }
    /**
     * @param array $uniqueViews
     * @param VisitableInterface $visitable
     */
    private function saveVisitableType(array $uniqueViews, VisitableInterface $visitable)
    {
        $uniqueViews[$visitable->getVisitableType()] = array();
        $this->saveVisitableId($uniqueViews, $visitable);
    }
    /**
     * @return mixed
     */
    private function getUniqueViewsVariable()
    {        
        return $this->session->get(self::SESSION_ARRAY_VARIABLE);
    }

}
