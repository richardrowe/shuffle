<?php

namespace Shuffle\CardBundle\Tests\Handler;

use Shuffle\CardBundle\Handler\CardHandler;
use Shuffle\CardBundle\Model\CardInterface;
use Shuffle\CardBundle\Entity\Card;
use Shuffle\CardBundle\Entity\Deck;

class CardHandlerTest extends \PHPUnit_Framework_TestCase
{

    const CARD_CLASS = 'Shuffle\CardBundle\Tests\Handler\SampleCard';

    /** @var CardHandler */
    protected $cardHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::CARD_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::CARD_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::CARD_CLASS));
    }

   public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $cards = $this->getCards(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($cards));

        $this->cardHandler = $this->createCardHandler($this->om, static::CARD_CLASS,  $this->formFactory);

        $all = $this->cardHandler->all($limit, $offset);

        $this->assertEquals($cards, $all);
    }

    public function testGet()
    {
        $id = 1;
        $card = $this->getCard();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($card));

        $this->cardHandler = $this->createCardHandler($this->om, static::CARD_CLASS,  $this->formFactory);

        $this->cardHandler->get($id);
    }

    public function testPost()
    {
        $front = 'aima';
        $back = 'interval';
        $deck = new Deck();

        $parameters = array('front' => $front, 'back' => $back, 'deck' => $deck);

        $card = $this->getCard();
        $card->setFront($front);
        $card->setBack($back);
        $card->setDeck($deck);

        $form = $this->getMock('Shuffle\CardBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($card));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->cardHandler = $this->createCardHandler($this->om, static::CARD_CLASS, $this->formFactory);
        $cardObject = $this->cardHandler->post($parameters);

        $this->assertEquals($cardObject, $card);
    }

    /**
     * @expectedException Shuffle\CardBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $front = 'aima';
        $back = 'interval';
        $deck = new Deck();

        $parameters = array('front' => $front, 'back' => $back, 'deck' => $deck);

        $card = $this->getCard();
        $card->setFront($front);
        $card->setBack($back);
        $card->setDeck($deck);

        $form = $this->getMock('Shuffle\CardBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->cardHandler = $this->createCardHandler($this->om, static::CARD_CLASS,  $this->formFactory);
        $this->cardHandler->post($parameters);
    }

    public function testPut()
    {
        $front = 'aima';
        $back = 'interval';
        $deck = new Deck();

        $parameters = array('front' => $front, 'back' => $back, 'deck' => $deck);

        $card = $this->getCard();
        $card->setFront($front);
        $card->setBack($back);
        $card->setDeck($deck);

        $form = $this->getMock('Shuffle\CardBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($card));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->cardHandler = $this->createCardHandler($this->om, static::CARD_CLASS,  $this->formFactory);
        $cardObject = $this->cardHandler->put($card, $parameters);

        $this->assertEquals($cardObject, $card);
    }

    public function testPatch()
    {
        $front = 'aima';
        $back = 'interval';
        $deck = new Deck();

        $parameters = array('front' => $front, 'deck' => $deck);

        $card = $this->getCard();
        $card->setFront($front);
        $card->setBack($back);
        $card->setDeck($deck);

        $form = $this->getMock('Shuffle\CardBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($card));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->cardHandler = $this->createCardHandler($this->om, static::CARD_CLASS,  $this->formFactory);
        $cardObject = $this->cardHandler->patch($card, $parameters);

        $this->assertEquals($cardObject, $card);
    }


    protected function getCards($maxCards = 5)
    {
        $cards = array();
        for($i = 0; $i < $maxCards; $i++) {
            $cards[] = $this->getCard();
        }

        return $cards;
    }

    protected function createCardHandler($objectManager, $cardClass, $formFactory)
    {
        return new CardHandler($objectManager, $cardClass, $formFactory);
    }

    protected function getCard()
    {
        $cardClass = static::CARD_CLASS;

        return new $cardClass();
    }
}

class SampleCard extends Card
{
}