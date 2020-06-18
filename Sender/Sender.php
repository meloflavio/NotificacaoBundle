<?php


namespace MeloFlavio\NotificacaoBundle\Sender;


use MeloFlavio\NotificacaoBundle\Entity\NotificacaoBase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class Sender implements SenderInterface
{

    private $publisher;
    private $serializer;
    private $container;

    public function __construct(PublisherInterface $publisher,SerializerInterface $serializer, ContainerInterface $container) {
        $this->publisher = $publisher;
        $this->serializer = $serializer;
        $this->container = $container;
    }


    public function send(NotificacaoBase $notificacao, $topic = null)
    {
        if(is_null($topic)){
            $topic = get_class($notificacao);
        }
        $serializado = $this->serializer->serialize($notificacao, 'json', [
            'attributes' => ['id', 'icone','texto','forBlock'=>[array_keys($notificacao->getForBlock())], 'createdBy'=>['username','firstName','lastname'], 'created']
        ]);
//
        $this->publish( sprintf("/{$topic}/%s",$notificacao->getHistoria()->getId()),$serializado);
    }

    public function sendObject( $jsonData, $username,$topic = 'global')
    {
//
        $this->publish( sprintf("/{$topic}/%s",$username),$jsonData);
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
}