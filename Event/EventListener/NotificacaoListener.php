<?php
namespace Meloflavio\NotificacaoBundle\Event\EventListener;


use App\Entity\ComentarioHistoria;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use MeloFlavio\NotificacaoBundle\Entity\NotificacaoBase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class NotificacaoListener implements EventSubscriber
{

    private $publisher;
    private $serializer;
    private $container;

    public function __construct(PublisherInterface $publisher,SerializerInterface $serializer, ContainerInterface $container) {
        $this->publisher = $publisher;
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

    public function sendMessage(LifecycleEventArgs $eventArgs, $canal = 'message_historia'){

        /** @var NotificacaoBase $notificacao */
        $notificacao = $eventArgs->getObject();

        $username = $notificacao->getCreatedBy()->getUsername();

        $serializado = $this->serializer->serialize($notificacao, 'json', [
            'attributes' => ['id', 'icone','texto','forBlock'=>[array_keys($notificacao->getForBlock())], 'createdBy'=>['username','firstName','lastname'], 'created']
        ]);
//
        $this->publish( sprintf("/{$canal}/%s",$notificacao->getHistoria()->getId()),$serializado);

        return ;

    }

    public function  publish($topic,$data){
        $postData = http_build_query([
            'topic' => $topic,
            'data' => $data,
        ]);

        file_get_contents($this->container->getParameter('mercure_url'), false, stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\nAuthorization: Bearer ".$this->container->getParameter('mercure_token'),
            'content' => $postData,
        ]]));

    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {

        if ($eventArgs->getEntity() instanceof NotificacaoBase) {
            $this->sendMessage($eventArgs);
        }

    }

}