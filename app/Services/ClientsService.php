<?php

namespace App\Services;

class ClientsService
{
    public function __construct(array $clientsHttp = [])
    {
        $this->redis = new \Predis\Client('redis:6379');
        $this->clients = $this->getClients($clientsHttp);
    }

    /**
     * Проверяем статистику на количество записей источников с баллом больше 10
     * а потом сравнивает с количеством всех источников, если они совпадают, то берем списки источников
     * с редиса, которые уже сортированы по баллам (успешным)
     *
     * @param array|$clientsHttp
     *
     * @return array
     */
    private function getClients(array $clientsHttp): array
    {
        $this_date = date('Ymd');
        $client_list_redis = $this->redis->zrevrangebyscore(
            'stats:' . $this_date,
            '+inf',
            '10',
        ); // все записи у которых балл выше 10

        /**
         * Если равенство совпало, это будет означать,
         * в статистике отметился, каждый источник не менее 10 раз
         */
        if (count($client_list_redis) === count($clientsHttp)) {
            $namespace_array = $client_list_redis;
        } else {
            shuffle(
                $clientsHttp,
            ); // статический массив всегда рандомно перемешиваем
            $namespace_array = $clientsHttp;
        }

        $arrayClients = [];
        foreach ($namespace_array as $clientsHttp) {
            $arrayClients[] = $clientsHttp;
        }

        return $arrayClients;
    }

    /**
     * Получаем список объектов которые сохранены в Redis
     *
     * @return array
     */
    public function getRedisClientsStats()
    {
        $this_date = date('Ymd');
        return $this->redis->zrevrangebyscore(
            'stats:' . $this_date,
            '+inf',
            '0',
            'WITHSCORES',
        );
    }

    public function getOrganizationByInn(int $inn): ?array
    {
        foreach ($this->clients as $item) {
            $start = microtime(true);

            // имитация отрицательного ответа или долгого ответа
            switch (rand(0, 2)) {
                case 0:
                    $response = (new $item)->sendRequest($inn);
                    break;
                case 1;
                    $response = false;
                    break;
                case 2;
                    $response = (new $item)->sendRequest($inn);
                    sleep(1);
                    break;
            }

            /**
             * если ответ отрицательный или долгий, то переходим к другому источнику
             */
            if (!$response or (microtime(true) - $start) > 1) {
                continue;
            } else {
                $class_name = $item;
                $this->setRedisClient($class_name);
                return $response;
            }
        }
        return null;
    }

    /**
     * Функция записывает название класса в редис
     *
     * @param string $class_name
     */
    private function setRedisClient(string $class_name): void
    {
        $this_date = date('Ymd');
        $this->redis->zincrby('stats:' . $this_date, 1, $class_name);
    }
}
