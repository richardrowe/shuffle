<?php

namespace Shuffle\CardBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use Shuffle\CardBundle\Model\DeckInterface;
use Shuffle\CardBundle\Form\DeckType;
use Shuffle\CardBundle\Exception\InvalidFormException;

class DeckHandler implements DeckHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a list of Decks.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Get a Deck.
     *
     * @param mixed $id
     *
     * @return DeckInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create a new Deck.
     *
     * @param array $parameters
     *
     * @return DeckInterface
     */
    public function post(array $parameters)
    {
        $deck = $this->createDeck();

        // Validate and hydrate the Deck Object.
        return $this->processForm($deck, $parameters, 'POST');
    }

    /**
     * Edit a Deck, or create if not exist.
     *
     * @param DeckInterface $deck
     * @param array         $parameters
     *
     * @return DeckInterface
     */
    public function put(DeckInterface $deck, array $parameters)
    {
        return $this->processForm($deck, $parameters, 'PUT');
    }

    /**
     * Partially update a Deck.
     *
     * @param DeckInterface $deck
     * @param array         $parameters
     *
     * @return DeckInterface
     */
    public function patch(DeckInterface $deck, array $parameters)
    {
        return $this->processForm($deck, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param DeckInterface $deck
     * @param array         $parameters
     * @param String        $method
     *
     * @return DeckInterface
     *
     * @throws \Shuffle\CardBundle\Exception\InvalidFormException
     */
    private function processForm(DeckInterface $deck, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new DeckType(), $deck, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $deck = $form->getData();
            $this->om->persist($deck);
            $this->om->flush($deck);

            return $deck;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createDeck()
    {
         return new $this->entityClass();
    }
}
