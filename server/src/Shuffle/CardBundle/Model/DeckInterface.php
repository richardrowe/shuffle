<?php

namespace Shuffle\CardBundle\Model;

Interface DeckInterface
{
    /**
     * Set title
     *
     * @param string $title
     * @return PageInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle();
}