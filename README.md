```
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use ChrisLoftus\Reqres\ReqresAdapter;

class FooController extends Controller
{
    public function bar()
    {
        $reqresAdapter = new ReqresAdapter;
        $user = $reqresAdapter->getUser(2);

        dump($user->firstName);
    }
}

```
