<?php


namespace MeloFlavio\NotificacaoBundle\Model;


interface NotificacaoInterface
{


    /**
     * @return bool
     */
     public function isLida();

    /**
     * @param bool $lida
     * @return self
     */
    public function setLida(bool $lida);

}