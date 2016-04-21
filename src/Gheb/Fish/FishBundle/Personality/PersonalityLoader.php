<?php
namespace Gheb\Fish\FishBundle\Personality;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 * @todo multiple personality
 */
Class PersonalityLoader
{
    public static function load($personality = 'average')
    {
        $yml = new Parser();

        try {
            $personality = $yml->parse(file_get_contents(__DIR__.'/../Personality/'.$personality.'.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the personality YML: %s", $e->getMessage());
        }

        return $personality;
    }
}
