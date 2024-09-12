<?php

declare(strict_types=1);

namespace ChrisLoftus\Plentific\DataTransferObjects;

use JsonSerializable;

final readonly class UserCreated implements JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $job,
        public string $createdAt
    ) {
        //
    }

    public static function fromArray(array $data): UserCreated
    {
        return new UserCreated(
            $data['id'],
            $data['name'],
            $data['job'],
            $data['createdAt']
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
            'name' => $this->name,
            'job' => $this->job,
            'createdAt' => $this->createdAt
        ];
    }
}
