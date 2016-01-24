<?php
namespace Gheb\Tamagotchi\CoreBundle\Inputs;

use Gheb\Tamagotchi\CoreBundle\Character\Character;

/**
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
     * @var integer
     */
    private $action;
    /**
     * @var Character
     */
    private $character;
    /**
     * @var \DateTime
     */
    private $takenAt;
    /**
     * @var string
     */
    private $takenBy;

    public function __construct(Character $character, $action, $takenBy)
    {
        $this->takenAt = new \DateTime();
        $this->takenBy = $takenBy;
        $this->action = $action;
        $this->character = $character;
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
     * @return Character
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * @param Character $character
     */
    public function setCharacter($character)
    {
        $this->character = $character;
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
}
