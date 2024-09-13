# Framework agnostic Reqres.in library

## Laravel example

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ChrisLoftus\Reqres\Adapters\ReqresAdapter;

class UserController extends Controller
{
    public function __construct(
        private ReqresAdapter $reqresAdapter
    ) {
        //
    }

    public function index(Request $request): void
    {
        // Inline validation for example purposes
        $validated = $request->validate(['page' => ['integer', 'min:1']]);

        $page = $validated['page'] ?? 1;

        try {
            $users = $this->reqresAdapter->getUsersPaginated($page);
    
            dump($users->page);
            dump($users->nextPage);
            dump($users->data[0]?->email);
            dump($users->data[0]?->firstName);
            // etc...
        } catch (ReqresException $e) {
            // Catches all exceptions related to the library only
            // Handle exception...
        }

    }

    public function show(int $id): void
    {
        try {
            $user = $this->reqresAdapter->getUser($id);

            dump($user->email);
            dump($user->firstName);
            dump($user->lastName);
            dump($user->avatar);
            // etc...
        } catch (ReqresException $e) {
            // Catches all exceptions related to the library only
            // Handle exception...
        }
    }

    public function store(Request $request)
    {
        // Inline validation for example purposes
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255']
            'job' => ['required', 'string', 'max:255'],
        ]);

        try {
            $user = $this->reqresAdapter->createUser($validated['name'], $validated['job']);
    
            dump($user->id);
            dump($user->name);
            dump($user->job);
            dump($user->createdAt);
        } catch (ReqresException $e) {
            // Catches all exceptions related to the library only
            // Handle exception...
        }
    }
}

```
