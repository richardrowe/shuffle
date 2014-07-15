<?php

namespace Shuffle\CardBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Shuffle\CardBundle\Tests\Fixtures\Entity\LoadCardData;
use Shuffle\CardBundle\Tests\Fixtures\Entity\LoadDeckData;

class CardControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->auth = array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'userpass',
        );

        $this->client = static::createClient(array(), $this->auth);
    }

    public function testJsonPostCardAction()
    {
        $fixtures = array('Shuffle\CardBundle\Tests\Fixtures\Entity\LoadCardData');
        $this->loadFixtures($fixtures);
        $cards = LoadCardData::$cards;
        $card = array_pop($cards);

        $this->client->request(
            'POST',
            sprintf('/api/v1/decks/%d/cards.json', $card->getDeck()->getId()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"front": "aima", "back": "interval"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

	public function testJsonPostCardActionShouldReturn400WithBadParameters()
	{
        $fixtures = array('Shuffle\CardBundle\Tests\Fixtures\Entity\LoadCardData');
        $this->loadFixtures($fixtures);
        $cards = LoadCardData::$cards;
        $card = array_pop($cards);

	    $this->client = static::createClient();
	    $this->client->request(
	        'POST',
	        sprintf('/api/v1/decks/%d/cards.json', $card->getDeck()->getId()),
	        array(),
	        array(),
	        array('CONTENT_TYPE' => 'application/json'),
            '{"fronts": "aima", "back": "interval"}'
	    );

	    $this->assertJsonResponse($this->client->getResponse(), 400, false);
	}

    public function testJsonPutCardActionShouldModify()
    {
        $fixtures = array('Shuffle\CardBundle\Tests\Fixtures\Entity\LoadCardData');
        $this->loadFixtures($fixtures);
        $cards = LoadCardData::$cards;
        $card = array_pop($cards);

        $this->client->request(
            'GET', 
            sprintf('/api/v1/decks/%d/cards/%d.json', 
            $card->getDeck()->getId(), $card->getId()), array('ACCEPT' => 'application/json')
        );
        $this->assertEquals(
            200, 
            $this->client->getResponse()->getStatusCode(), 
            $this->client->getResponse()->getContent()
        );

        $this->client->request(
            'PUT',
            sprintf('/api/v1/decks/%d/cards/%d.json', $card->getDeck()->getId(), $card->getId()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"front": "aima", "back": "interval"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/v1/decks/%d/cards/%d.json', $card->getDeck()->getId(), $card->getId())
            ),
            $this->client->getResponse()->headers
        );
    }

    public function testJsonPutCardActionShouldCreate()
    {
        $fixtures = array('Shuffle\CardBundle\Tests\Fixtures\Entity\LoadCardData');
        $this->loadFixtures($fixtures);
        $cards = LoadCardData::$cards;
        $card = array_pop($cards);

        $id = 0;
        $this->client->request('GET', sprintf('/api/v1/decks/%d/cards/%d.json', $card->getDeck()->getId(), $id), array('ACCEPT' => 'application/json'));

        $this->assertEquals(
            404, 
            $this->client->getResponse()->getStatusCode(), 
            $this->client->getResponse()->getContent()
        );

        $this->client->request(
            'PUT',
            sprintf('/api/v1/decks/%d/cards/%d.json', $card->getDeck()->getId(), $id),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"front": "aima", "back": "interval"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPatchCardAction()
    {
        $fixtures = array('Shuffle\CardBundle\Tests\Fixtures\Entity\LoadCardData');
        $this->loadFixtures($fixtures);
        $cards = LoadCardData::$cards;
        $card = array_pop($cards);

        $this->client->request(
            'PATCH',
            sprintf('/api/v1/decks/%d/cards/%d.json', $card->getDeck()->getId(), $card->getId()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"front": "aima"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/v1/decks/%d/cards/%d.json', $card->getDeck()->getId(), $card->getId())
            ),
            $this->client->getResponse()->headers
        );
    }

    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );

        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
}