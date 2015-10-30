<?php

namespace Krombox\CommonBundle\Security;

//use Kf\KitBundle\Model\Traits\WithDataClass;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


abstract class AbstractVoter implements VoterInterface
{
    //use WithDataClass;

    const SUPPORTED_CLASS = '';

    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    static $attributes = array(self::VIEW, self::EDIT, self::DELETE);
    private $user;
    private $entity;

    abstract function dispatch($attribute);

    public function vote(TokenInterface $token, $entity, array $attributes)
    {
        if (!$this->supportsEntity($entity)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $attribute = $this->processAttributes($attributes);
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        $this->user   = $token->getUser();
        $this->entity = $entity;

        return $this->dispatch($attribute) ?: self::ACCESS_DENIED;
    }

    public function supportsAttribute($attribute)
    {
        return in_array(
            $attribute,
            static::$attributes
        );
    }

    protected function supportsEntity($entity){
        if(!is_object($entity)) {
            return false;
        }
        return $this->supportsClass(get_class($entity));
    }

    public function supportsClass($class)
    {
        $dataClass = static::DATA_CLASS ?: static::SUPPORTED_CLASS;
        if (is_array($dataClass)) {
            foreach ($dataClass as $check) {
                if ($check === $class || is_subclass_of($class, $check)) {
                    return true;
                }
            }

            return false;
        } else {
            return $dataClass === $class || is_subclass_of($class, $dataClass);
        }
    }


    private function processAttributes($attributes)
    {
        if (1 !== count($attributes)) {
            throw new InvalidArgumentException(
                'It\'s allowed only one attribute for VIEW or EDIT'
            );
        }

        return $attributes[0];
    }

    protected function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    protected function getEntity()
    {
        return $this->entity;
    }

    protected function checkIf($check)
    {
        return $check ? self::ACCESS_GRANTED : self::ACCESS_DENIED;
    }
}
