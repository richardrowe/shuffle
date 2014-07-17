<?php

namespace Shuffle\CardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadCardData extends DataFixtureLoader implements DependentFixtureInterface
{
    /**
     * Returns an array of file paths to fixtures
     *
     * @return array<string>
     */
    protected function getFixtures()
    {
        return array(
            __DIR__ . '/card.yml',
        );
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return array(
            'Shuffle\CardBundle\DataFixtures\ORM\LoadDeckData',
        );
    }
}
