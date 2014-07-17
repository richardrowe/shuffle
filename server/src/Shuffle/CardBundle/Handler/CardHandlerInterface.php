<?php

namespace Shuffle\CardBundle\Handler;

use Shuffle\CardBundle\Model\CardInterface;

interface CardHandlerInterface
{
    /**
     * Get a list of Cards.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($deck_id, $limit = 5, $offset = 0);

    /**
     * Get a Card.
     *
     * @param mixed $id
     *
     * @return CardInterface
     */
    public function get($id);

    /**
     * Post Card, creates a new Card.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return CardInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Card.
     *
     * @api
     *
     * @param CardInterface   $card
     * @param array           $parameters
     *
     * @return CardInterface
     */
    public function put(CardInterface $card, array $parameters);

    /**
     * Partially update a Card.
     *
     * @api
     *
     * @param CardInterface   $card
     * @param array           $parameters
     *
     * @return CardInterface
     */
    public function patch(CardInterface $card, array $parameters);
}