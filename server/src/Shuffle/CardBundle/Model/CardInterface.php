<?php

namespace Shuffle\CardBundle\Model;

Interface CardInterface
{
    /**
     * Set back
     *
     * @param string $title
     * @return PageInterface
     */
    public function setBack($back);

    /**
     * Get back
     *
     * @return string 
     */
    public function getBack();

    /**
     * Set front
     *
     * @param string $title
     * @return PageInterface
     */
    public function setFront($front);

    /**
     * Get front
     *
     * @return string 
     */
    public function getFront();

    /**
     * Set deck
     *
     * @param \Shuffle\CardBundle\Entity\Deck $deck
     * @return Card
     */
    public function setDeck(\Shuffle\CardBundle\Entity\Deck $deck = null);

    /**
     * Get deck
     *
     * @return \Shuffle\CardBundle\Entity\Deck 
     */
    public function getDeck();
}