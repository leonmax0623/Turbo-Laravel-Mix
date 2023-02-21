<?php

namespace App\Services\Clients;

use App\Exceptions\UsedInOtherTableException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Client;
use Illuminate\Support\Str;
use Throwable;

class ClientService
{
    /**
     * @param  array  $filter
     * @return LengthAwarePaginator
     */
    public function getPaginatedClients(array $filter): LengthAwarePaginator
    {
        $clients = Client::with('department');

        if (!empty($filter['name'])) {
            $clients->where(
                function ($query) use ($filter) {
                    $search = '%'.$filter['name'].'%';
                    $query->where('name', 'like', $search)
                        ->orWhere('surname', 'like', $search)
                        ->orWhere('middle_name', 'like', $search);
                }
            );
        }

        if (!empty($filter['search'])) {
            $clients->where(
                function ($query) use ($filter) {
                    $search = '%'.$filter['search'].'%';
                    $query->where('name', 'like', $search)
                        ->orWhere('surname', 'like', $search)
                        ->orWhere('middle_name', 'like', $search)
                        ->orWhere('emails', 'like', $search)
                        ->orWhere('phones', 'like', $search)
                        ->orWhere('passport', 'like', $search);
                }
            );
        }

        if (!empty($filter['number'])) {
            $clients->whereHas('cars',
                function ($query) use ($filter) {
                    $search = '%'.Str::upper($filter['number']).'%';
                    $query->where('number', 'like', $search);
                }
            );
        }

        if (!empty($filter['order'])) {
            if ($filter['order'] === 'surname') {
                $clients->orderBy('surname')->orderBy('name');
            } elseif ($filter['order'] === 'name') {
                $clients->orderBy('name')->orderBy('surname');
            } else { // id or other
                $clients->orderBy('id', 'desc');
            }
        } else {
            $clients->orderBy('id', 'desc');
        }

        if (!empty($filter['department_id'])) {
            $clients->where('department_id', $filter['department_id']);
        }

        return $clients->paginate(30);
    }

    /**
     * @param array $data
     * @return Client
     * @throws UsedInOtherTableException
     */
    public function store(array $data): Client
    {
        $allPhones = array_merge(...Client::pluck('phones')->toArray());

        $this->checkPhones(data_get($data, 'phones', []), $allPhones);

        return Client::create($data);
    }

    /**
     * @param  int  $clientId
     * @return Client
     */
    public function getClientById(int $clientId): Client
    {
        return Client::findOrFail($clientId);
    }

    /**
     * @param int $clientId
     * @param array $data
     * @return Client
     * @throws UsedInOtherTableException
     */
    public function update(int $clientId, array $data): Client
    {
        $allPhones = array_merge(...Client::where('id', '!=', $clientId)->pluck('phones')->toArray());

        $this->checkPhones(data_get($data, 'phones', []), $allPhones);

        $client = $this->getClientById($clientId);
        $client->update($data);

        return $client;
    }

    /**
     * @param  int  $clientId
     * @throws UsedInOtherTableException
     */
    public function delete(int $clientId): void
    {
        try {
            Client::where('id', $clientId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Клиента нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }

    /**
     * @throws UsedInOtherTableException
     */
    public function checkPhones(array $phones, array $allPhones) {
        foreach ($allPhones as $phone) {
            if (in_array($phone, $phones)) {
                throw new UsedInOtherTableException("номер '$phone' уже добавлен для другого клиента", 422);
            }
        }
    }
}
