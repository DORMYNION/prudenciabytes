<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Exports\Support\Results;

use FI\Modules\Clients\Models\Client;

class Clients implements SourceInterface
{
    public function getResults($params = [])
    {
        $client = Client::orderBy('name');

        return $client->get()->toArray();
    }
}