<?php
namespace Krombox\MainBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Krombox\CommonBundle\Model\VisitableInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("krombox.visitable_manager")
 */
class VisitableManager {
    
    private $om;
    
    /**
     * @DI\InjectParams({
           "om" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    /**
     * {@inheritdoc}
     */
    public function update(VisitableInterface $visitable)
    {
        $visitable->incrementViewsCount();
        
        $this->om->persist($visitable);
        $this->om->flush();
    }
} 