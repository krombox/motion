<?php

namespace Krombox\MainBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Event\RatingEvent;
use Krombox\MainBundle\Event\RatingEvents;
use Krombox\MainBundle\Event\PlaceEvent;
use Krombox\MainBundle\Event\PlaceEvents;
use Krombox\PaymentBundle\Event\OrderMembershipEvent;
use Krombox\PaymentBundle\Event\OrderMembershipEvents;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\Rating;
use Krombox\PaymentBundle\Entity\OrderMembership;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @DI\Service
 * @DI\DoctrineListener(
 *     events = {"onFlush", "postFlush", "postRemove"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0
 * )
 */
class PlaceDispatcher
{
    /** @var  EventDispatcherInterface */
    protected $eventDispatcher;
    private $events = [];
    private $disabled = false;

    /**
     * @DI\InjectParams({
     *     "eventDispatcher" = @DI\Inject("event_dispatcher"),
     * })
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function postRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();        
        
        if($entity instanceof Rating){
            $event = new RatingEvent($entity);
            $this->eventDispatcher->dispatch(RatingEvents::POST_REMOVE, $event);
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {                    
        if ($this->disabled || empty($this->events)) {
            return;
        }
        $this->disabled = true;
        foreach ($this->events as $k => $event) {
            if($event instanceof RatingEvent){
                $this->eventDispatcher->dispatch(RatingEvents::POST_SAVE, $event);
            } elseif ($event instanceof PlaceEvent) {
                $this->eventDispatcher->dispatch(PlaceEvents::POST_SAVE, $event);
            } elseif ($event instanceof OrderMembershipEvent) {
                $this->eventDispatcher->dispatch(OrderMembershipEvents::POST_SAVE, $event);
            }
            unset($this->events[$k]);
        }
        $this->disabled = false;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        if ($this->disabled) {
            return;
        }
        //$entity = $args->;die();
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            //var_dump(get_class($entity));//die();
            if ($entity instanceof Place) {
                $this->addPlace($entity, false, $uow->getEntityChangeSet($entity));
            } elseif ($entity instanceof Rating) {
                $this->addRating($entity, false, $uow->getEntityChangeSet($entity));
            } elseif ($entity instanceof OrderMembership) {
                $this->addOrderMembership($entity, false, $uow->getEntityChangeSet($entity));
            }
        }//die();
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Place) {
                $this->addPlace($entity, true, $uow->getEntityChangeSet($entity));
            } elseif ($entity instanceof Rating) {
                $this->addRating($entity, true, $uow->getEntityChangeSet($entity));
            }
        }
        
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof Place) {
                //$this->addPlace($entity, true, $uow->getEntityChangeSet($entity));
            } elseif ($entity instanceof Rating) {
                $this->removeRating($entity, true, $uow->getEntityChangeSet($entity));
            }
        }
    }

    protected function addPlace(Place $entity, $isNew, $changeset)
    {
        $ev = new PlaceEvent($entity, $changeset, $isNew);
        $this->eventDispatcher->dispatch(PlaceEvents::PRE_SAVE, $ev);
        $this->events[] = $ev;
    }
    
    protected function addRating(Rating $entity, $isNew, $changeset)
    {
        //$ev = new FeedbackEvent($entity, $changeset, $isNew);
        $ev = new RatingEvent($entity);
        $this->eventDispatcher->dispatch(RatingEvents::PRE_SAVE, $ev);
        $this->events[] = $ev;
    }
    
    protected function removeRating(Rating $entity, $isNew, $changeset)
    {        
        $ev = new RatingEvent($entity);
        $this->eventDispatcher->dispatch(RatingEvents::PRE_REMOVE, $ev);
        //$this->events[] = $ev;
    }
    
    protected function addOrderMembership(OrderMembership $entity, $isNew, $changeset)
    {
        //$ev = new FeedbackEvent($entity, $changeset, $isNew);
        $ev = new OrderMembershipEvent($entity);
        $this->eventDispatcher->dispatch(OrderMembershipEvents::PRE_SAVE, $ev);
        $this->events[] = $ev;
    }
}
