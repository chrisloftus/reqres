<?php

declare(strict_types=1);

namespace ChrisLoftus\Reqres\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\Attributes\Test;
use ChrisLoftus\Reqres\ReqresAdapter;
use ChrisLoftus\Reqres\DataTransferObjects\User;
use ChrisLoftus\Reqres\DataTransferObjects\UserCreated;
use ChrisLoftus\Reqres\DataTransferObjects\UsersPaginated;

final class ReqresAdapterTest extends TestCase
{
    #[Test]
    public function get_user_by_id(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{
                "data": {
                    "id": 2,
                    "email": "janet.weaver@reqres.in!",
                    "first_name": "Janet",
                    "last_name": "Weaver",
                    "avatar": "https://reqres.in/img/faces/2-image.jpg"
                },
                "support": {
                    "url": "https://reqres.in/#support-heading",
                    "text": "To keep ReqRes free, contributions towards server costs are appreciated!"
                }
            }')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $adapter = new ReqresAdapter($client);
        $response = $adapter->getUser(2);

        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals(2, $response->id);
        $this->assertEquals('janet.weaver@reqres.in!', $response->email);
    }

    #[Test]
    public function create_user(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{
                "name": "morpheus",
                "job": "leader",
                "id": "972",
                "createdAt": "2024-09-12T19:23:53.547Z"
            }')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $adapter = new ReqresAdapter($client);
        $response = $adapter->createUser('morpheus', 'leader');

        var_dump($response);

        $this->assertInstanceOf(UserCreated::class, $response);
        $this->assertEquals(972, $response->id);
    }

    #[Test]
    public function get_users_paginated(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{
                "page": 2,
                "per_page": 6,
                "total": 12,
                "total_pages": 2,
                "data": [
                    {
                        "id": 7,
                        "email": "michael.lawson@reqres.in",
                        "first_name": "Michael",
                        "last_name": "Lawson",
                        "avatar": "https://reqres.in/img/faces/7-image.jpg"
                    },
                    {
                        "id": 8,
                        "email": "lindsay.ferguson@reqres.in",
                        "first_name": "Lindsay",
                        "last_name": "Ferguson",
                        "avatar": "https://reqres.in/img/faces/8-image.jpg"
                    },
                    {
                        "id": 9,
                        "email": "tobias.funke@reqres.in",
                        "first_name": "Tobias",
                        "last_name": "Funke",
                        "avatar": "https://reqres.in/img/faces/9-image.jpg"
                    },
                    {
                        "id": 10,
                        "email": "byron.fields@reqres.in",
                        "first_name": "Byron",
                        "last_name": "Fields",
                        "avatar": "https://reqres.in/img/faces/10-image.jpg"
                    },
                    {
                        "id": 11,
                        "email": "george.edwards@reqres.in",
                        "first_name": "George",
                        "last_name": "Edwards",
                        "avatar": "https://reqres.in/img/faces/11-image.jpg"
                    },
                    {
                        "id": 12,
                        "email": "rachel.howell@reqres.in",
                        "first_name": "Rachel",
                        "last_name": "Howell",
                        "avatar": "https://reqres.in/img/faces/12-image.jpg"
                    }
                ],
                "support": {
                    "url": "https://reqres.in/#support-heading",
                    "text": "To keep ReqRes free, contributions towards server costs are appreciated!"
                }
            }')
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $adapter = new ReqresAdapter($client);
        $response = $adapter->getUsersPaginated(2);

        var_dump($response);

        $this->assertInstanceOf(UsersPaginated::class, $response);
        $this->assertEquals(2, $response->page);
        $this->assertInstanceOf(User::class, $response->data[0]);
        $this->assertEquals('michael.lawson@reqres.in', $response->data[0]->email);
    }
}