<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testCreateAccountService(){
        $client = static::createClient();
        $crawler = $client->request('POST',
            '/service/account/create',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest'),
            '{"accountNumber": "0000000"}'
        );


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $contentArray  = json_decode($client->getResponse()->getContent());
        $this->assertEquals("success", $contentArray->status);
        $this->assertEquals("00000000", $contentArray->data->account->accountNumber);

    }
}
