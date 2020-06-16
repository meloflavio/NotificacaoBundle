<?php
namespace MeloFlavio\NotificacaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Base
 * @ORM\MappedSuperclass()
 */
abstract class NotificacaoBase
{
    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icone = 'fa fa-envelope';

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
     * @ORM\ManyToOne(targetEntity="Sonata\UserBundle\Model\UserInterface")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;
    /**
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Sonata\UserBundle\Model\UserInterface")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    protected $updatedBy;


    public function __construct()
    {
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
     * @return \Sonata\UserBundle\Model\UserInterface
     */
    public function getCreatedBy(): ?\Sonata\UserBundle\Model\UserInterface
    {
        return $this->createdBy;
    }

    /**
     * @param \Sonata\UserBundle\Model\UserInterface $createdBy
     */
    public function setCreatedBy(\Sonata\UserBundle\Model\UserInterface $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \Sonata\UserBundle\Model\UserInterface
     */
    public function getUpdatedBy(): ?\Sonata\UserBundle\Model\UserInterface
    {
        return $this->updatedBy;
    }

    /**
     * @param \Sonata\UserBundle\Model\UserInterface $updatedBy
     */
    public function setUpdatedBy(\Sonata\UserBundle\Model\UserInterface $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
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
     * @return array
     */
    abstract public function getForBlock();
}
