<?php

namespace Shuffle\CardBundle\Tests\Fixtures\Entity;

use Shuffle\CardBundle\Entity\Deck;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadDeckData implements FixtureInterface
{
    static public $decks = array();

    public function load(ObjectManager $manager)
    {
        $deck = new Deck();
        $deck->setTitle('title');

        $manager->persist($deck);
        $manager->flush();

        self::$decks[] = $deck;
    }
}