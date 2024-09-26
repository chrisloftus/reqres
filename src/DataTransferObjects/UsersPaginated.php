<?php

declare(strict_types=1);

namespace ChrisLoftus\Reqres\DataTransferObjects;

use JsonSerializable;

readonly final class UsersPaginated implements JsonSerializable
{
    public function __construct(
        public int $page,
        public int $perPage,
        public int $total,
        public int $totalPages,
        public ?int $nextPage,
        public ?int $prevPage,
        /** @var array<int, User> */
        public array $data
    ) {
        //
    }

    public static function fromArray(array $data): UsersPaginated
    {
        return new UsersPaginated(
            page: $data['page'],
            perPage: $data['per_page'],
            total: $data['total'],
            totalPages: $data['total_pages'],
            nextPage: $data['page'] < $data['total_pages'] ? $data['page'] + 1 : null,
            prevPage: $data['page'] > 1 ? $data['page'] - 1 : null,
            data: array_map(fn ($user) => User::fromArray(($user)), $data['data'])
        );
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'perPage' => $this->perPage,
            'total' => $this->total,
            'totalPages' => $this->totalPages,
            'nextPage' => $this->nextPage,
            'prevPage' => $this->prevPage,
            'data' => $this->data
        ];
    }
}
