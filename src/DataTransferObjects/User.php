<?php

declare(strict_types=1);

namespace ChrisLoftus\Reqres\DataTransferObjects;

use JsonSerializable;

readonly final class User implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $avatar
    ) {
        //
    }

    public static function fromArray(array $data): User
    {
        return new User(
            $data['id'],
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $data['avatar']
        );
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'avatar' => $this->avatar
        ];
    }
}
