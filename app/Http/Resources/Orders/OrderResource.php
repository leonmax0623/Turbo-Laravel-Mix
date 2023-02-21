<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\AppealReasons\AppealReasonResource;
use App\Http\Resources\Cars\SimpleCarResource;
use App\Http\Resources\Clients\SimpleClientResource;
use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\Departments\DepartmentResource;
use App\Http\Resources\Documents\DocumentForOrderResource;
use App\Http\Resources\Finances\FinanceOrderResource;
use App\Http\Resources\Processes\ProcessCategoryResource;
use App\Http\Resources\Products\ProductRequestForOrderResource;
use App\Http\Resources\Tasks\TaskForOrderResource;
use App\Http\Resources\Users\SimpleUserResource;
use App\Http\Resources\Works\WorkForOrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Order $this */
        return [
            'id'                => $this->id,
            'user'              => SimpleUserResource::make($this->user),
            'client'            => SimpleClientResource::make($this->client),
            'appeal_reason'     => AppealReasonResource::make($this->appealReason),
            'car'               => SimpleCarResource::make($this->car),
            'stage'             => OrderStageResource::make($this->orderStage),
            'process_category'  => ProcessCategoryResource::make($this->processCategory),
            'department'        => DepartmentResource::make($this->department),
            'product_requests'  => ProductRequestForOrderResource::collection($this->productRequests),
            'tasks'             => TaskForOrderResource::collection($this->tasks),
            'works'             => WorkForOrderResource::collection($this->works),
            'finances'          => FinanceOrderResource::collection($this->finances),
            'documents'         => DocumentForOrderResource::collection($this->documents),
            'comments'          => CommentResource::collection($this->comments),
            'comment'           => $this->comment,
            'total_sum'         => $this->totalSum,
            'total_paid_sum'    => $this->totalPaidSum,
            'total_pay_sum'     => $this->totalPaySum,
            'created_at'        => db_to_date($this->created_at, 'd.m.Y H:i'),
            'updated_at'        => db_to_date($this->updated_at, 'd.m.Y H:i'),
        ];
    }
}
