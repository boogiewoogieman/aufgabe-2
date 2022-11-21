<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventTest extends WebTestCase {

  public function testCreateEventAndRetrieveInfo(): void {
    $client = static::createClient();

    $client->jsonRequest('POST', '/event/create', [
      'title' => 'Christmas',
      'date' => '2022-12-24',
      'city' => 'Christmas Town',
    ]);
    $this->assertResponseIsSuccessful();

    $response = $client->getResponse();
    $content = $response->getContent();

    $this->assertJson($content);

    $result = json_decode($content, TRUE);

    $this->assertArrayHasKey('eventId', $result);

    // get saved information

    $client->jsonRequest('GET', '/event/' . $result['eventId']);
    $this->assertResponseIsSuccessful();

    $response = $client->getResponse();
    $content = $response->getContent();

    $this->assertJson($content);

    $result = json_decode($content, TRUE);

    $this->assertArrayHasKey('result', $result);
    $this->assertArrayHasKey('id', $result['result']);
    $this->assertArrayHasKey('title', $result['result']);
    $this->assertArrayHasKey('date', $result['result']);
    $this->assertArrayHasKey('city', $result['result']);
  }

  public function testListEvents(): void {
    $client = static::createClient();
    $client->jsonRequest('GET', '/event/list');
    $response = $client->getResponse();

    $this->assertResponseIsSuccessful();

    $content = $response->getContent();

    $this->assertJson($content);

    $result = json_decode($content, TRUE);

    $this->assertArrayHasKey('result', $result);
    $this->assertIsArray($result['result']);
  }

}
