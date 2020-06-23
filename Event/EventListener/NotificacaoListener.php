<?php
namespace MeloFlavio\NotificacaoBundle\Event\EventListener;


use App\Entity\ComentarioHistoria;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MeloFlavio\NotificacaoBundle\Entity\NotificacaoBase;
use MeloFlavio\NotificacaoBundle\Sender\Sender;
use MeloFlavio\NotificacaoBundle\Sender\SenderInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class NotificacaoListener implements EventSubscriber
{

    private $sender;
    private $serializer;
    private $container;

    public function __construct(SenderInterface $sender,SerializerInterface $serializer, ContainerInterface $container) {
        $this->sender = $sender;
        $this->serializer = $serializer;
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return array(Events::postPersist);
    }

    public function sendMessage(LifecycleEventArgs $eventArgs){

        /** @var NotificacaoBase $notificacao */
        $notificacao = $eventArgs->getObject();

//
        $this->sender->send($notificacao,'message_historia',$notificacao->getHistoria()->getId());

        return ;

    }


    public function postPersist(LifecycleEventArgs $eventArgs)
    {

        if ($eventArgs->getEntity() instanceof NotificacaoBase) {
            $this->sendMessage($eventArgs);
        }

    }

}