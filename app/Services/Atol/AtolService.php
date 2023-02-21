<?php

namespace App\Services\Atol;

use App\Exceptions\UsedInOtherTableException;
use App\Models\AtolLog;
use App\Models\Finance;
use App\Models\ProductRequest;
use App\Models\Work;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class AtolService
{

    /**
     * @throws UsedInOtherTableException
     */
    public function prepareShift($type): array|null
    {

        $uuid = Str::uuid();

        $receipt = [
            'uuid' => $uuid,
            'request' => [
                'type' => $type,
                'operator' => [
                    'name' => "АРУТЮНЯН АСМИК МИХАЙЛОВНА",
                    'vatin' => "231139389283"
                ],
            ]
        ];

        $request = Http::post(config('app.atol_web_server_url') . 'requests', $receipt);

        if (data_get($request->json(), 'error.description')) {
            throw new UsedInOtherTableException(
                data_get($request->json(), 'error.description'), 422
            );
        } else {
            sleep(10);
        }

        $response = Http::get(config('app.atol_web_server_url') . 'requests/' . $uuid)->json();

        if (data_get($response, 'results.0.error.code') > 0) {
            throw new UsedInOtherTableException(
                data_get($response, 'results.0.error.description'), 422
            );
        }

        $response['uuid'] = $uuid;
        return $response;
    }

    /**
     * @throws UsedInOtherTableException
     */
    public function openShift(): array|null
    {
        return $this->prepareShift('openShift');
    }

    /**
     * @throws UsedInOtherTableException
     */
    public function closeShift(): array|null
    {
        return $this->prepareShift('closeShift');
    }

    /**
     * @param $modelClass
     * @return array
     */
    public function collectBySells($modelClass): array
    {

        $items = [];
        $total = 0;

        /** @var Finance $modelClass */

        $order = $modelClass?->order;
        $productRequests = $order?->productRequests ?? [];
        $works = $order?->works ?? [];

        foreach ($productRequests as $productRequest) {

            /** @var ProductRequest $productRequest */
            $product = $productRequest?->product;

            if (!data_get($product, 'id')) {
                continue;
            }

            $items[] = [
                'type' => 'position', //if position must be key amount
                'name' => $product->name,
                'price' => data_get($productRequest, 'sum', 0),
                'department' => 350901,
                'quantity' => data_get($productRequest, 'count', 0),
                'amount' => data_get($productRequest, 'sum', 0) * data_get($productRequest, 'count', 0),
                'paymentObject' => 'commodity',
                'paymentMethod' => 'fullPayment',
                'tax' => [
                    'type' => 'none'
                ]
            ];

            $total += data_get($productRequest, 'sum', 0) * data_get($productRequest, 'count', 0);
        }

        foreach ($works as $work) {

            /** @var Work $work */
            if (!data_get($work, 'id')) {
                continue;
            }

            $items[] = [
                'type' => 'position', //if position must be key amount
                'name' => $work->name,
                'price' => $work->sum,
                'department' => 350901,
                'quantity' => 1,
                'amount' => $work->sum,
                'paymentObject' => 'service',
                'paymentMethod' => 'fullPayment',
                'tax' => [
                    'type' => 'none'
                ]
            ];

            $total += $work->sum;
        }

        return [
            'items' => $items,
            'total' => $total
        ];
    }

    /**
     * @param $modelClass
     * @param string $payment
     * @return array
     */
    public function collectBySellsPrePayment($modelClass, string $payment): array
    {

        $items = [];
        $total = 0;

        /** @var Finance $modelClass */

        $order = $modelClass?->order;
        $productRequests = $order?->productRequests ?? [];
        $works = $order?->works ?? [];

        if (count($productRequests) > 0) {

            $customSum = $modelClass->sum / count($productRequests);

            foreach ($productRequests as $productRequest) {

                /** @var ProductRequest $productRequest */
                $product = $productRequest?->product;

                if (!data_get($product, 'id')) {
                    continue;
                }

                $items[] = [
                    'type' => 'position', //if position must be key amount
                    'name' => $product->name,
                    'price' => $customSum,
                    'department' => 350901,
                    'quantity' => 1,
                    'amount' => $customSum,
                    'paymentObject' => 'commodity',
                    'paymentMethod' => $payment,
                    'tax' => [
                        'type' => 'none'
                    ]
                ];

                $total += $customSum;
            }

        } else if (count($works) > 0) {
            foreach ($works as $work) {

                $customSum = $modelClass->sum / count($works);

                /** @var Work $work */
                if (!data_get($work, 'id')) {
                    continue;
                }

                $items[] = [
                    'type' => 'position', //if position must be key amount
                    'name' => $work->name,
                    'price' => $customSum,
                    'department' => 350901,
                    'quantity' => 1,
                    'amount' => $customSum,
                    'paymentObject' => 'service',
                    'paymentMethod' => $payment,
                    'tax' => [
                        'type' => 'none'
                    ]
                ];

                $total += $customSum;
            }
        }

        if ($total !== (double)$modelClass->sum) {
            $items[] = [
                'type' => 'position', //if position must be key amount
                'name' => 'Услуга',
                'price' => $modelClass->sum - $total,
                'department' => 350901,
                'quantity' => 1,
                'amount' => $modelClass->sum - $total,
                'paymentObject' => 'service',
                'paymentMethod' => 'prepayment',
                'tax' => [
                    'type' => 'none'
                ]
            ];

            $total += $modelClass->sum - $total;
        }

        return [
            'items' => $items,
            'total' => $total
        ];
    }

    /**
     * @param int $id
     * @param string $model
     * @param string $resource
     * @return JsonResource
     * @throws UsedInOtherTableException
     */
    public function payment(int $id, string $model, string $resource): JsonResource
    {

        try {
            /** @var Finance $model */
            /** @var Finance $modelClass */

            $modelClass = $model::query()->find($id);

            if (!data_get($modelClass, 'id')) {
                throw new UsedInOtherTableException('Модель не найден');
            } else if (data_get($modelClass, 'status') === 'inProgress') {
                throw new UsedInOtherTableException('Оплата в процессе. Проверьте статус оплаты');
            }

            if (in_array($modelClass->operation_type, [Finance::OPERATION_SELL, Finance::OPERATION_SELL_RETURN])) {

                if ($modelClass->operation_type === Finance::OPERATION_SELL_RETURN && $modelClass->sum > $modelClass->order?->totalPaidSum) {
                    throw new UsedInOtherTableException('Возврат не может быть больше оплаченной суммы. Оплаченная сумма: ' . number_format($modelClass->order?->totalPaidSum, 2, '.', ' '));
                }

                if ($modelClass->sum === $modelClass->order?->totalSum) { // Оплата 100%
                    $result = $this->collectBySells($modelClass);
                } else { // Предоплата
                    $payment = $modelClass->order?->totalPaidSum >= $modelClass->order?->totalSum ? 'fullPrepayment' : 'prepayment';

                    $result = $this->collectBySellsPrePayment($modelClass, $payment);
                }

                $total = data_get($result, 'total');
                $items = data_get($result, 'items');

            } else if(in_array($modelClass->operation_type, [Finance::OPERATION_BUY, Finance::OPERATION_BUY_RETURN])) {

                $total = $modelClass->sum;
                $items = [
                    [
                        'type' => 'position',
                        'name' => $modelClass->financeGroup?->name,
                        'department' => 350901,
                        'price' => $total,
                        'quantity' => 1,
                        'amount' => $total,
                        'tax' => [
                            'type' => 'none'
                        ]
                    ]
                ];

            } else {
                throw new UsedInOtherTableException('Тип операции не выбран');
            }

//             $total = 1;
//             $items = [
//                 [
//                     'type' => 'position',
//                     'name' => 'name',
//                     'price' => 1,
//                     'quantity' => 1,
//                     'amount' => 1,
//                     'department' => 350901,
//                     'paymentMethod' => 'advance',
//                     'tax' => [
//                         'type' => 'none'
//                     ]
//                 ]
//             ];

            if ($total === 0) {
                throw new UsedInOtherTableException('Общая сумма равна 0', 422);
            } else if (count($items) === 0) {
                throw new UsedInOtherTableException('Нет продуктов или услуг для проведения оплаты', 422);
            } else if (!$modelClass?->operation_type) {
                throw new UsedInOtherTableException('Не указан тип операции', 422);
            } else if (!$modelClass?->payment_type) {
                throw new UsedInOtherTableException('Не указан тип оплаты', 422);
            }

            $uuid = Str::uuid();
            $receipt = [
                'uuid' => $uuid,
                'request' => [
                    'type' => $modelClass->operation_type,
                    'ignoreNonFiscalPrintErrors' => false,
                    'taxationType' => 'patent',
                    'operator' => [
                        'name' => "АРУТЮНЯН АСМИК МИХАЙЛОВНА",
                        'vatin' => "231139389283"
                    ],
                    'items' => $items,
                    'payments' => [
                        [
                            'type' => $modelClass->payment_type,
                            'sum'  => $total,
                        ]
                    ],
                    'total' => $total,
                ]
            ];

            $order = $modelClass->order;

            if (data_get($order, 'id')) {
                $receipt['request']['clientInfo'] = [
                    'emailOrPhone' => data_get($order?->client?->phones, '0') ?? data_get($order?->client?->emails, '0')
                ];
                //$receipt['request']['electronically'] = true;
            }

            $request = Http::post(config('app.atol_web_server_url') . 'requests', $receipt);

            if(empty($request->json())) {
                throw new UsedInOtherTableException(
                    'Нет связи с атолом', 422
                );
            }

            if (in_array(data_get($request->json(), 'results.0.error.code'), [68, 144])) {
                $this->closeShift();
                $this->payment($id, $model, $resource);
            }

            if(data_get($request->json(), 'error.description')) {
                throw new UsedInOtherTableException(
                    data_get($request->json(), 'error.description'), 422
                );
            } else {
                sleep(10);
            }

            $response = Http::get(config('app.atol_web_server_url') . 'requests/' . data_get($request->json(), 'uuid'));

            if (in_array(data_get($response->json(), 'results.0.error.code'), [68, 144])) {
                $this->closeShift();
                $this->payment($id, $model, $resource);
            }

            AtolLog::query()->create([
                'entity_id' => $modelClass->id,
                'entity_type' => $model,
                'operation_type' => $modelClass->operation_type,
                'sum' => $modelClass->sum,
                'status' => data_get($response->json(), 'results.0.status'),
                'data' => [
                    'request' => $request->json(),
                    'response' => $response->json(),
                    'receipt' => $receipt,
                ],
            ]);

            if (data_get($response->json(), 'results.0.error.code') > 0) {
                $modelClass->update([
                    'status' => data_get($response->json(), 'results.0.status')
                ]);
                throw new UsedInOtherTableException(
                    data_get($response->json(), 'results.0.error.description'), 422
                );
            }

            $modelClass->update([
                'status' => data_get($response->json(), 'results.0.status')
            ]);

            /** @var JsonResource $resource */
            return $resource::make(Finance::query()->find($modelClass->id));
        } catch (Throwable $e) {
            throw new UsedInOtherTableException(
                $e->getMessage(), $e->getCode()
            );
        }
    }

    /**
     * @throws UsedInOtherTableException
     */
    public function paymentStatus(int $id, string $model, string $resource): JsonResource
    {
        /** @var Finance $model */
        /** @var Finance $modelClass */

        $modelClass = $model::query()->find($id);

        $log = AtolLog::query()->where([
            ['entity_id', $id],
            ['entity_type', $model]
        ])->orderBy('id', 'desc')->first();

        if (!data_get($log, 'id')) {
            throw new UsedInOtherTableException('Результат не найден');
        }

        $response = Http::get(config('app.atol_web_server_url') . 'requests/' . data_get($log, 'data.request.uuid'));

        /** @var AtolLog $log */
        $data = [
            'request'  => data_get($log->data, 'request'),
            'response' => $response->json(),
        ];

        if (data_get($response->json(), 'results.0.error.code') > 0) {
            throw new UsedInOtherTableException(
                data_get($response->json(), 'results.0.error.description'), 422
            );
        }

        $modelClass->update([
            'status' => data_get($data, 'response.results.0.status')
        ]);

        $log->update([
            'data' => $data,
            'status' => data_get($response->json(), 'results.0.status'),
        ]);
        /** @var JsonResource $resource */
        return $resource::make($modelClass);
    }

}
