<?php
namespace MeloFlavio\NotificacaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;

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
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var Usuario $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserInterface")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;
    /**
     * @var Usuario updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UserInterface")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    private $updatedBy;


    public function __construct()
    {
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return UserInterface
     */
    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy): void
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
