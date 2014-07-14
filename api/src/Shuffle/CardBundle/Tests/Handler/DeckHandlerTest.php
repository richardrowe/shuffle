<?php

namespace Shuffle\CardBundle\Tests\Handler;

use Shuffle\CardBundle\Handler\DeckHandler;
use Shuffle\CardBundle\Model\DeckInterface;
use Shuffle\CardBundle\Entity\Deck;

class DeckHandlerTest extends \PHPUnit_Framework_TestCase
{

    const DECK_CLASS = 'Shuffle\CardBundle\Tests\Handler\SampleDeck';

    /** @var DeckHandler */
    protected $deckHandler;
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
            ->with($this->equalTo(static::DECK_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DECK_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DECK_CLASS));
    }

   public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $decks = $this->getDecks(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($decks));

        $this->deckHandler = $this->createDeckHandler($this->om, static::DECK_CLASS,  $this->formFactory);

        $all = $this->deckHandler->all($limit, $offset);

        $this->assertEquals($decks, $all);
    }

    public function testGet()
    {
        $id = 1;
        $deck = $this->getDeck();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($deck));

        $this->deckHandler = $this->createDeckHandler($this->om, static::DECK_CLASS,  $this->formFactory);

        $this->deckHandler->get($id);
    }

    public function testPost()
    {
        $title = 'japanese';

        $parameters = array('title' => $title);

        $deck = $this->getDeck();
        $deck->setTitle($title);

        $form = $this->getMock('Shuffle\CardBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($deck));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->deckHandler = $this->createDeckHandler($this->om, static::DECK_CLASS, $this->formFactory);
        $deckObject = $this->deckHandler->post($parameters);

        $this->assertEquals($deckObject, $deck);
    }

    /**
     * @expectedException Shuffle\CardBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'japanese';

        $parameters = array('title' => $title);

        $deck = $this->getDeck();
        $deck->setTitle($title);

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

        $this->deckHandler = $this->createDeckHandler($this->om, static::DECK_CLASS,  $this->formFactory);
        $this->deckHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';

        $parameters = array('title' => $title);

        $deck = $this->getDeck();
        $deck->setTitle($title);

        $form = $this->getMock('Shuffle\CardBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($deck));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->deckHandler = $this->createDeckHandler($this->om, static::DECK_CLASS,  $this->formFactory);
        $deckObject = $this->deckHandler->put($deck, $parameters);

        $this->assertEquals($deckObject, $deck);
    }

    public function testPatch()
    {
        $title = 'title1';

        $parameters = array('title' => $title);

        $deck = $this->getDeck();
        $deck->setTitle($title);

        $form = $this->getMock('Shuffle\CardBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($deck));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->deckHandler = $this->createDeckHandler($this->om, static::DECK_CLASS,  $this->formFactory);
        $deckObject = $this->deckHandler->patch($deck, $parameters);

        $this->assertEquals($deckObject, $deck);
    }


    protected function getDecks($maxDecks = 5)
    {
        $decks = array();
        for($i = 0; $i < $maxDecks; $i++) {
            $decks[] = $this->getDeck();
        }

        return $decks;
    }

    protected function createDeckHandler($objectManager, $deckClass, $formFactory)
    {
        return new DeckHandler($objectManager, $deckClass, $formFactory);
    }

    protected function getDeck()
    {
        $deckClass = static::DECK_CLASS;

        return new $deckClass();
    }
}

class SampleDeck extends Deck
{
}