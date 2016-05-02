<?php

/**
 * This file is part of the FairPlay package.
 *
 * (c) Oeil Pour Oeil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gheb\Fish\FishBundle\Services;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class AbstractLifeObligation
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Services
 */
interface AbstractLifeObligation
{
    public static function applyEffect(Fish &$fish);
}