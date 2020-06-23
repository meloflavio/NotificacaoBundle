<?php


namespace MeloFlavio\NotificacaoBundle\Sender;



use MeloFlavio\NotificacaoBundle\Entity\NotificacaoBase;

interface SenderInterface
{

    /**
     * Sends the given message.
     * @param NotificacaoBase $notificacao
     * @param null $topic
     * @param null $topicId
     */
    public function send(NotificacaoBase $notificacao,$topic = null,$topicId = null);

}