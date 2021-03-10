<?php
namespace App\Services;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token=$token;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW=>['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function getAuthenticatedUser(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult(); // récupère l'entité qui a déclenché l'évènement
        $method = $event->getRequest->getMethod(); // récupère la méthode invoqué dans la request
        $adherent = $this->token->getToken()->getUser(); // récupère l'adhérent actuellement connecté qui a lancé la request
        if ($entity instanceof Pret && $method == Request::METHOD_POST) { // s'il s'agit bien d'une opération POST sur l'entity Pret
            $entity->setAdherent($adherent); // on écrit l'adhérent dans la propriété de l'entity Pret
        }
        return;
    }
}