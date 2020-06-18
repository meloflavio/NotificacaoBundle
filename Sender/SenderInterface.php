<?php


namespace MeloFlavio\NotificacaoBundle\Sender;



use MeloFlavio\NotificacaoBundle\Entity\NotificacaoBase;

interface SenderInterface
{

    /**
     * Sends the given message.
     */
    public function send(NotificacaoBase $notificacao,$topic = null);

}