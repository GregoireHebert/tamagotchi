<?php
namespace Gheb\Tamagotchi\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class LogSupplies
{
    const ACTION_PLAY               = 1;
    const ACTION_TURN_OFF_LIGHT     = 2;
    const ACTION_CLEAN              = 3;
    const ACTION_FEED               = 4;
    const ACTION_GIVE_MEDICINE      = 5;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var integer
     */
    private $action;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $takenAt;

    /**
     * @ORM\Column(type="string", length=256)
     * @var string
     */
    private $takenBy;

    public function __construct($action, $takenBy)
    {
        $this->takenAt = new \DateTime();
        $this->takenBy = $takenBy;
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param int $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return \DateTime
     */
    public function getTakenAt()
    {
        return $this->takenAt;
    }

    /**
     * @param \DateTime $takenAt
     */
    public function setTakenAt($takenAt)
    {
        $this->takenAt = $takenAt;
    }

    /**
     * @return string
     */
    public function getTakenBy()
    {
        return $this->takenBy;
    }

    /**
     * @param string $takenBy
     */
    public function setTakenBy($takenBy)
    {
        $this->takenBy = $takenBy;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
