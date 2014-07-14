<?php

namespace Shuffle\CardBundle\Handler;

use Shuffle\CardBundle\Model\DeckInterface;

interface DeckHandlerInterface
{
    /**
     * Get a list of Decks.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Get a Deck.
     *
     * @param mixed $id
     *
     * @return DeckInterface
     */
    public function get($id);

    /**
     * Post Deck, creates a new Deck.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return DeckInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Deck.
     *
     * @api
     *
     * @param DeckInterface   $deck
     * @param array           $parameters
     *
     * @return DeckInterface
     */
    public function put(DeckInterface $deck, array $parameters);

    /**
     * Partially update a Deck.
     *
     * @api
     *
     * @param DeckInterface   $deck
     * @param array           $parameters
     *
     * @return DeckInterface
     */
    public function patch(DeckInterface $deck, array $parameters);
}