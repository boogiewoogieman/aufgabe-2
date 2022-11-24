<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventTest extends WebTestCase {

  public function testCreateEventAndRetrieveInfo(): void {
    $client = static::createClient();

    $event = [
      'title' => 'Christmas',
      'date' => '2022-12-24',
      'city' => 'Christmas Town',
    ];

    $client->jsonRequest('POST', '/event/create', $event);
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
    $this->assertEquals($event['title'],$result['result']['title']);
    $this->assertArrayHasKey('date', $result['result']);
    $this->assertEquals($event['date'],$result['result']['date']);
    $this->assertArrayHasKey('city', $result['result']);
    $this->assertEquals($event['city'],$result['result']['city']);
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
