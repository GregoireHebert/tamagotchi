<?php
namespace Gheb\Tamagotchi\CoreBundle\Tests\Character;

use Gheb\Tamagotchi\CoreBundle\Character\Character;
use Gheb\Tamagotchi\CoreBundle\Personality\PersonalityLoader;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class CharacterTests extends \PHPUnit_Framework_TestCase
{
    public function testCharacterMood()
    {
        $character = new Character();
        $personality = PersonalityLoader::load();

        $character->setPersonality($personality);

        $this->assertEquals(Character::MOOD_STILL, $character->getMood(), 'bad mood');
  }
}
