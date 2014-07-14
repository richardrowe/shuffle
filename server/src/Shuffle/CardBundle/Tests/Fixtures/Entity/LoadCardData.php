<?php

namespace Shuffle\CardBundle\Tests\Fixtures\Entity;

use Shuffle\CardBundle\Entity\Card;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadCardData implements FixtureInterface
{
    static public $cards = array();

    public function load(ObjectManager $manager)
    {
        $card = new Card();
        $card->setTitle('title');

        $manager->persist($card);
        $manager->flush();

        self::$cards[] = $card;
    }
}