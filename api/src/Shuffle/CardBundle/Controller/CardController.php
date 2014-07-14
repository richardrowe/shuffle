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

use Shuffle\CardBundle\Form\CardType;
use Shuffle\CardBundle\Model\CardInterface;
use Shuffle\CardBundle\Exception\InvalidFormException;

class CardController extends FOSRestController
{

    /**
     * List all cards.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing cards.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many cards to return.")
     *
     * @Annotations\View(
     *  templateVar="cards"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getCardsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('shuffle_card.card.handler')->all($limit, $offset);
    }

    /**
     * Get single Card.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Card for a given id",
     *   output = "Shuffle\CardBundle\Entity\Card",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the card is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="card")
     *
     * @param Request $request the request object
     * @param int     $id      the card id
     *
     * @return array
     *
     * @throws NotFoundHttpException when card not exist
     */
    public function getCardAction($id)
    {
        $card = $this->getOr404($id);

        return $card;
    }

    /**
     * Create a Card from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new card from the submitted data.",
     *   input = "Shuffle\CardBundle\Form\CardType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "ShuffleCardBundle:Card:newCard.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postCardAction(Request $request)
    {
       try {
           $newCard = $this->container->get('shuffle_card.card.handler')->post(
               $request->request->all()
           );

           $routeOptions = array(
               'id' => $newCard->getId(),
               '_format' => $request->get('_format')
           );

           return $this->routeRedirectView('api_1_get_card', $routeOptions, Codes::HTTP_CREATED);
       } catch (InvalidFormException $exception) {

           return $exception->getForm();
       }
    }

    /**
     * Presents the form to use to create a new card.
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
    public function newCardAction()
    {
        return $this->createForm(new CardType());
    }

    /**
     * Update existing card from the submitted data or create a new card at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Shuffle\CardBundle\Form\CardType",
     *   statusCodes = {
     *     201 = "Returned when the Card is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "ShuffleCardBundle:Card:editCard.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the card id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when card not exist
     */
    public function putCardAction(Request $request, $id)
    {
        try {
            if (!($card = $this->container->get('shuffle_card.card.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $card = $this->container->get('shuffle_card.card.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $card = $this->container->get('shuffle_card.card.handler')->put(
                    $card,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $card->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_card', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing card from the submitted data or create a new card at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Acme\DemoBundle\Form\CardType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmeBlogBundle:Card:editCard.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the card id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when card not exist
     */
    public function patchCardAction(Request $request, $id)
    {
        try {
            $card = $this->container->get('shuffle_card.card.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $card->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_card', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Card or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return CardInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($card = $this->container->get('shuffle_card.card.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
        }

        return $card;
    }
}
