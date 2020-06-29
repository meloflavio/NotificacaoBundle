<?php


namespace MeloFlavio\NotificacaoBundle\Model;


interface NotificacaoInterface
{

    public const PESSOA = 1;
    public const GRUPO = 2;
    public const SISTEMA = 3;
    public const GLOBAL = 4;
    public const CUSTOM = 5;

    /**
     * @return bool
     */
     public function isLida();

    /**
     * @param bool $lida
     * @return self
     */
    public function setLida(bool $lida);

    /**
     * @return array
     */
    public function getForBlock();


}