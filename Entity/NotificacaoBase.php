<?php
namespace MeloFlavio\NotificacaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
use MeloFlavio\NotificacaoBundle\Model\NotificacaoInterface;

/**
 * Class Base
 * @ORM\MappedSuperclass()
 */
abstract class NotificacaoBase implements NotificacaoInterface
{

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $tipoDestinatario = NotificacaoBase::CUSTOM;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $destinatario;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $topic;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    protected $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    protected $icone = 'fa fa-envelope';

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;
    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column
     */
    protected $createdBy;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $lida = false;



    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getTipoDestinatario(): int
    {
        return $this->tipoDestinatario;
    }

    /**
     * @param int $tipoDestinatario
     */
    public function setTipoDestinatario(int $tipoDestinatario): void
    {
        $this->tipoDestinatario = $tipoDestinatario;
    }

    /**
     * @return mixed
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * @param mixed $destinatario
     */
    public function setDestinatario($destinatario): void
    {
        $this->destinatario = $destinatario;
    }



    /**
     * @return DateTime
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     */
    public function setUpdated(DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy): void
    {
        $this->createdBy = $createdBy;
    }


    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): void
    {
        $this->texto = $texto;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): void
    {
        $this->icone = $icone;
    }

    /**
     * @return bool
     */
    public function isLida(): bool
    {
        return $this->lida;
    }

    /**
     * @param bool $lida
     */
    public function setLida(bool $lida): void
    {
        $this->lida = $lida;
    }

    /**
     * @return mixed
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param mixed $topic
     */
    public function setTopic($topic): void
    {
        $this->topic = $topic;
    }

}
