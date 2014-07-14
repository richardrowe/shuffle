<?php

namespace Shuffle\CardBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use Shuffle\CardBundle\Model\CardInterface;
use Shuffle\CardBundle\Form\CardType;
use Shuffle\CardBundle\Exception\InvalidFormException;

class CardHandler implements CardHandlerInterface
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
     * Get a list of Cards.
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
     * Get a Card.
     *
     * @param mixed $id
     *
     * @return CardInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create a new Card.
     *
     * @param array $parameters
     *
     * @return CardInterface
     */
    public function post(array $parameters)
    {
        $card = $this->createCard();

        // Validate and hydrate the Card Object.
        return $this->processForm($card, $parameters, 'POST');
    }

    /**
     * Edit a Card, or create if not exist.
     *
     * @param CardInterface $card
     * @param array         $parameters
     *
     * @return CardInterface
     */
    public function put(CardInterface $card, array $parameters)
    {
        return $this->processForm($card, $parameters, 'PUT');
    }

    /**
     * Partially update a Card.
     *
     * @param CardInterface $card
     * @param array         $parameters
     *
     * @return CardInterface
     */
    public function patch(CardInterface $card, array $parameters)
    {
        return $this->processForm($card, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param CardInterface $card
     * @param array         $parameters
     * @param String        $method
     *
     * @return CardInterface
     *
     * @throws \Shuffle\CardBundle\Exception\InvalidFormException
     */
    private function processForm(CardInterface $card, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new CardType(), $card, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $card = $form->getData();
            $this->om->persist($card);
            $this->om->flush($card);

            return $card;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createCard()
    {
         return new $this->entityClass();
    }
}
