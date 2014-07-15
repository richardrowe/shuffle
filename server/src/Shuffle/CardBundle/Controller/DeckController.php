<?php

namespace Shuffle\CardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Shuffle\CardBundle\Form\DeckType;
use Shuffle\CardBundle\Model\DeckInterface;
use Shuffle\CardBundle\Exception\InvalidFormException;

class DeckController extends FOSRestController
{

    /**
     * List all decks.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing decks.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many decks to return.")
     *
     * @Annotations\View(
     *  templateVar="decks"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getDecksAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('shuffle_card.deck.handler')->all($limit, $offset);
    }

    /**
     * Get single Deck.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Deck for a given id",
     *   output = "Shuffle\CardBundle\Entity\Deck",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the deck is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="deck")
     *
     * @param Request $request the request object
     * @param int     $id      the deck id
     *
     * @return array
     *
     * @throws NotFoundHttpException when deck not exist
     */
    public function getDeckAction($id)
    {
        $deck = $this->getOr404($id);

        return $deck;
    }

    /**
     * Create a Deck from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new deck from the submitted data.",
     *   input = "Shuffle\CardBundle\Form\DeckType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "ShuffleCardBundle:Deck:newDeck.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postDeckAction(Request $request)
    {
       try {
           $newDeck = $this->container->get('shuffle_card.deck.handler')->post(
               $request->request->all()
           );

           $routeOptions = array(
               'id' => $newDeck->getId(),
               '_format' => $request->get('_format')
           );

           return $this->routeRedirectView('api_1_get_deck', $routeOptions, Codes::HTTP_CREATED);
       } catch (InvalidFormException $exception) {

           return $exception->getForm();
       }
    }

    /**
     * Presents the form to use to create a new deck.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newDeckAction()
    {
        return $this->createForm(new DeckType());
    }

    /**
     * Update existing deck from the submitted data or create a new deck at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Shuffle\CardBundle\Form\DeckType",
     *   statusCodes = {
     *     201 = "Returned when the Deck is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "ShuffleCardBundle:Deck:editDeck.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the deck id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when deck not exist
     */
    public function putDeckAction(Request $request, $id)
    {
        try {
            if (!($deck = $this->container->get('shuffle_card.deck.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $deck = $this->container->get('shuffle_card.deck.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $deck = $this->container->get('shuffle_card.deck.handler')->put(
                    $deck,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $deck->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_deck', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing deck from the submitted data or create a new deck at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Acme\DemoBundle\Form\DeckType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Deck:editDeck.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the deck id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when deck not exist
     */
    public function patchDeckAction(Request $request, $id)
    {
        try {
            $deck = $this->container->get('shuffle_card.deck.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $deck->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_deck', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Deck or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return DeckInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        $deck = $this->container->get('shuffle_card.deck.handler')->get($id);

        if (!$deck instanceof DeckInterface) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }

        return $deck;
    }
}
