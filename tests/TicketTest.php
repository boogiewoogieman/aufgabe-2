<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketTest extends WebTestCase {

  public function testCreateTicketAndRetrieveInfo(): void {
    $client = static::createClient();

    // 1. create event

    $client->jsonRequest('POST', '/event/create', [
      'title' => 'Christmas',
      'date' => '2022-12-24',
      'city' => 'Christmas Town',
    ]);
    $this->assertResponseIsSuccessful();
    $response = $client->getResponse();
    $content = $response->getContent();
    $result = json_decode($content, TRUE);

    // 2. create ticket

    $ticket = [
      'firstName' => 'Santa',
      'lastName' => 'Claus',
      'eventId' => $result['eventId'],
    ];

    $client->jsonRequest('POST', '/ticket/create', $ticket);
    $this->assertResponseIsSuccessful();

    $response = $client->getResponse();
    $content = $response->getContent();

    $this->assertJson($content);

    $result = json_decode($content, TRUE);

    $this->assertArrayHasKey('ticketId', $result);

    // 3. retrieve ticket info

    $client->jsonRequest('GET', '/ticket/' . $result['ticketId']);
    $this->assertResponseIsSuccessful();

    $response = $client->getResponse();
    $content = $response->getContent();

    $this->assertJson($content);

    $result = json_decode($content, TRUE);

    $this->assertArrayHasKey('result', $result);
    $this->assertArrayHasKey('id', $result['result']);
    $this->assertArrayHasKey('barcodeString', $result['result']);
    $this->assertArrayHasKey('firstName', $result['result']);
    $this->assertEquals($ticket['firstName'],$result['result']['firstName']);
    $this->assertArrayHasKey('lastName', $result['result']);
    $this->assertEquals($ticket['lastName'],$result['result']['lastName']);
  }

  public function testListTickets(): void {
    $client = static::createClient();
    $client->jsonRequest('GET', '/ticket/list');
    $response = $client->getResponse();

    $this->assertResponseIsSuccessful();

    $content = $response->getContent();

    $this->assertJson($content);

    $result = json_decode($content, TRUE);

    $this->assertArrayHasKey('result', $result);
    $this->assertIsArray($result['result']);
  }

}
