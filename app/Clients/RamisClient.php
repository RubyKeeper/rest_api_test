<?php

namespace App\Clients;

use App\Interfaces\ClientsHttpInterface;

class RamisClient implements ClientsHttpInterface
{
    use ClientTrait;

    public function sendRequest(int $inn): array
    {
        return ['kpp' => $this->getKpp(), 'org' => $this->getNameOrg()];
    }
}
