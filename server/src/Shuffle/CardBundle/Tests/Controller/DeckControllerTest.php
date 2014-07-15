<?php

namespace Shuffle\CardBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Shuffle\CardBundle\Tests\Fixtures\Entity\LoadDeckData;

class DeckControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->auth = array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'userpass',
        );

        $this->client = static::createClient(array(), $this->auth);
    }

    public function testJsonPostDeckAction()
    {
        $this->client->request(
            'POST',
            '/api/v1/decks.json',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"title1"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

	public function testJsonPostDeckActionShouldReturn400WithBadParameters()
	{
	    $this->client = static::createClient();
	    $this->client->request(
	        'POST',
	        '/api/v1/decks.json',
	        array(),
	        array(),
	        array('CONTENT_TYPE' => 'application/json'),
	        '{"bad":"parameters"}'
	    );

	    $this->assertJsonResponse($this->client->getResponse(), 400, false);
	}

    public function testJsonPutDeckActionShouldModify()
    {
        $fixtures = array('Shuffle\CardBundle\Tests\Fixtures\Entity\LoadDeckData');
        $this->loadFixtures($fixtures);
        $decks = LoadDeckData::$decks;
        $deck = array_pop($decks);

        $this->client->request(
            'GET', 
            sprintf('/api/v1/decks/%d.json', 
            $deck->getId()), array('ACCEPT' => 'application/json')
        );
        
        $this->assertEquals(
            200, 
            $this->client->getResponse()->getStatusCode(), 
            $this->client->getResponse()->getContent()
        );

        $this->client->request(
            'PUT',
            sprintf('/api/v1/decks/%d.json', $deck->getId()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"modify existing deck"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/v1/decks/%d.json', $deck->getId())
            ),
            $this->client->getResponse()->headers
        );
    }

    public function testJsonPutDeckActionShouldCreate()
    {
        $id = 0;
        $this->client->request('GET', sprintf('/api/v1/decks/%d.json', $id), array('ACCEPT' => 'application/json'));

        $this->assertEquals(
            404, 
            $this->client->getResponse()->getStatusCode(), 
            $this->client->getResponse()->getContent()
        );

        $this->client->request(
            'PUT',
            sprintf('/api/v1/decks/%d.json', $id),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"abc"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    public function testJsonPatchDeckAction()
    {
        $fixtures = array('Shuffle\CardBundle\Tests\Fixtures\Entity\LoadDeckData');
        $this->loadFixtures($fixtures);
        $decks = LoadDeckData::$decks;
        $deck = array_pop($decks);

        $this->client->request(
            'PATCH',
            sprintf('/api/v1/decks/%d.json', $deck->getId()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"we only have one field"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 204, false);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/v1/decks/%d.json', $deck->getId())
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