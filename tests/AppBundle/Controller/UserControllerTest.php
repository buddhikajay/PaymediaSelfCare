<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    //request(
    //$method,
    //$uri,
    //array $parameters = array(),
    //array $files = array(),
    //array $server = array(),
    //$content = null,
    //$changeHistory = true
    //)

    public function testRegistrationServiceAction(){
        $client = static::createClient();
        $crawler = $client->request('POST',
            '/registrationService',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest'),
            '{"nic":"00000000", "accountNumber": "phoneNumber":"070000000", "imei":"1234"}'
        );


        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}
