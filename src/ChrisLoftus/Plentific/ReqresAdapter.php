<?php

declare (strict_types=1);

namespace ChrisLoftus\Plentific;

use Exception;
use GuzzleHttp\Client;
use ChrisLoftus\Plentific\Logger;
use ChrisLoftus\Plentific\DataTransferObjects\User;
use ChrisLoftus\Plentific\DataTransferObjects\UserCreated;
use ChrisLoftus\Plentific\DataTransferObjects\UsersPaginated;

class ReqresAdapter
{
    private const BASE_URL = 'https://reqres.in/api';

    public function __construct(
        private Client $client
    ) {
    }

    public function getUser(int $id): User
    {
        try {
            $response = $this->client->get(self::BASE_URL."/users/{$id}");

            $json = json_decode($response->getBody()->getContents(), true)['data'];

            return User::fromArray($json);
        } catch (Exception $e) {
            $log = Logger::getInstance();
            $log->error('Failed to get user', ['exception' => $e]);

            throw new Exception('Failed to get user');
        }
    }
    
    public function createUser(string $name, string $job): UserCreated
    {
        if (empty(trim($name)) || empty(trim($job))) {
            throw new Exception("User's name and job must be provided when creating a user");
        }

        try {
            $response = $this->client->post(self::BASE_URL.'/users', [
                'json' => [
                    'name' => $name,
                    'job' => $job
                ]
            ]);

            $json = json_decode($response->getBody()->getContents(), true);

            return UserCreated::fromArray($json);
        } catch (Exception $e) {
            $log = Logger::getInstance();
            $log->error('Failed to create user', ['exception' => $e]);

            throw new Exception('Failed to create user');
        }
    }

    public function getUsersPaginated(int $page): UsersPaginated
    {
        try {
            $response = $this->client->get(self::BASE_URL."/users?page={$page}");

            $json = json_decode($response->getBody()->getContents(), true);

            return UsersPaginated::fromArray($json);
        } catch (Exception $e) {
            $log = Logger::getInstance();
            $log->error('Failed to get users (paginated)', ['exception' => $e]);

            throw new Exception('Failed to get users (paginated)');
        }
    }
}
