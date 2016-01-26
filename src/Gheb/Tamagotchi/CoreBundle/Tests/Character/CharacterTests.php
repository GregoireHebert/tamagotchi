<?php
namespace Gheb\Tamagotchi\CoreBundle\Tests\Fish;

use Gheb\Tamagotchi\CoreBundle\Entity\Fish;
use Gheb\Tamagotchi\CoreBundle\Personality\PersonalityLoader;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class FishTests extends \PHPUnit_Framework_TestCase
{
    public function testFishMood()
    {
        $character = new Fish();
        $personality = PersonalityLoader::load();

        $character->setPersonality($personality);

        $this->assertEquals(Fish::MOOD_STILL, $character->getMood(), 'bad mood');
  }
}
