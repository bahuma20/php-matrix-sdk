<?php

namespace MatrixPhp\Tests;

use MatrixPhp\Exceptions\MatrixException;
use MatrixPhp\Exceptions\MatrixHttpLibException;
use MatrixPhp\Exceptions\ValidationException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use MatrixPhp\MatrixClient;
use MatrixPhp\Room;
use MatrixPhp\User;

class UserTest extends BaseTestCase {
    const HOSTNAME = "http://localhost";
    protected $userId = "@test:localhost";
    protected $roomId = '!test:localhost';

    protected MatrixClient $client;

    protected User $user;

    protected Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new MatrixClient(self::HOSTNAME);
        $this->user = new User($this->client->api(), $this->userId);
        $this->room = $this->invokePrivateMethod($this->client, 'mkRoom', [$this->roomId]);
    }

    public function testDisplayName() {
        // No displayname
        $displayname = 'test';
        $this->assertEquals($this->user->userId(), $this->user->getDisplayName($this->room));
        $container = [];
        $handler = $this->getMockClientHandler([new Response(200, [], '{}')], $container);
        $this->client->api()->setClient(new Client(['handler' => $handler]));
        $this->assertEquals($this->user->userId(), $this->user->getDisplayName());
        $this->assertEquals(1, count($container));


//        $mapi->whoami();
//        /** @var Request $req */
//        $req = array_get($container, '0.request');
    }

    public function testDisplayNameGlobal() {
        $displayname = 'test';

        // Get global displayname
        $container = [];
        $str = sprintf('{"displayname": "%s"}', $displayname);
        $handler = $this->getMockClientHandler([new Response(200, [], $str)], $container);
        $this->client->api()->setClient(new Client(['handler' => $handler]));
        $this->assertEquals($displayname, $this->user->getDisplayName());
        $this->assertEquals(1, count($container));

    }
}
