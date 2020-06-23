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

    protected function getTopic(NotificacaoBase $notificacao){
        $topic= $this->container->getParameter('meloflavio_notificacao.topic.default');
        $topicId = '';
        if(is_null($topic)){
            $topic = get_class($notificacao);
            $topicId = $notificacao->getCreatedBy()->getUsername();
        }else{
            $parameterId = $this->container->getParameter('meloflavio_notificacao.topic.parameter_id');
            if(!is_null($parameterId)){
                $parameterGet= 'get'.ucfirst($parameterId);
                $topicId = $notificacao->$parameterGet();
            }
        }
        return sprintf("/{$topic}/%s",$topicId);
    }

    public function send(NotificacaoBase $notificacao, $topic = null,$topicId = null)
    {
        if($notificacao->getCreatedBy() == null){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $notificacao->setCreatedBy($user);
        }

        if(is_null($topic)){
            $publishTopic = $this->getTopic($notificacao);
        }else{
            $publishTopic = sprintf("/{$topic}/%s",$topicId);
        }
        $serializado = $this->serializer->serialize($notificacao, 'json', [
            'attributes' => ['id', 'icone','texto','forBlock'=>[array_keys($notificacao->getForBlock())], 'createdBy'=>['username','firstName','lastname'], 'created']
        ]);
        $this->publish( $publishTopic,$serializado);
    }

    public function sendObject( $jsonData, $username, $topic = 'global')
    {
        $this->publish( sprintf("/{$topic}/%s",$username),$jsonData);
    }

    public function sendGlobalObject( $jsonData)
    {
        $this->publish( "/global",$jsonData);
    }

    public function sendGlobalUserNotification( $username,$title, $text)
    {

        if( $this->container->hasParameter('APP_NAME')){
            $system = $this->container->hasParameter('APP_NAME');
            $text = $system.' diz: '.$text;
        }
        $this->sendObject( json_encode(['title'=>$title,'text'=>$text ]));
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