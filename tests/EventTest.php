<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventTest extends WebTestCase {

  public function testCreateEvent(): void {
    $client = static::createClient();
    $client->jsonRequest('POST', '/event/create', [
      'title' => 'Christmas',
      'date' => '2022-12-24 12:00:00Z+1',
      'city' => 'Christmas Town',
    ]);
    $this->assertResponseIsSuccessful();

    $response = $client->getResponse();

    $content = $response->getContent();

    $this->assertJson($content);

    $result = json_decode($content, true);

    $this->assertArrayHasKey('eventId', $result);
  }

  public function testListEvents(): void {
    $client = static::createClient();
    $client->jsonRequest('GET', '/event/list');
    $response = $client->getResponse();

    $this->assertResponseIsSuccessful();

    $content = $response->getContent();

    $this->assertJson($content);
    $this->assertJsonStringEqualsJsonString($content, '[]');
  }

}
