<?php

namespace Shuffle\CardBundle\Tests\Fixtures\Entity;

use Shuffle\CardBundle\Entity\Card;
use Shuffle\CardBundle\Entity\Deck;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadCardData implements FixtureInterface
{
    static public $cards = array();

    public function load(ObjectManager $manager)
    {
        $deck = new Deck();
        $deck->setTitle('title');

        $card = new Card();
        $card->setBack('back');
        $card->setFront('front');
        $card->setDeck($deck);

        $manager->persist($card);
        $manager->flush();

        self::$cards[] = $card;
    }
}