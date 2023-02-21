<?php

namespace App\Http\Controllers\Api\Documents;

use App\Exceptions\CustomValidationException;
use App\Exceptions\UsedInOtherTableException;
use App\Http\Requests\Document\StoreRequest;
use App\Http\Requests\Document\UpdateRequest;
use App\Http\Resources\Documents\DocumentResource;
use App\Models\Document;
use App\Models\Order;
use App\Services\Documents\DocumentGenerateService;
use App\Services\Documents\DocumentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\RoleConst;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * DocumentController constructor.
     * @param  DocumentService  $documentService
     */
    public function __construct(private DocumentService $documentService)
    {
        $this->middleware([
            'permission:' . RoleConst::PERMISSION_DOCUMENTS_CRUD,
            'permission:' . RoleConst::PERMISSION_ORDERS_CRUD,
        ]);
    }

    /**
     * Список документов
     *
     * Права: `crud document`
     *
     * @group Документы
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $documents = $this->documentService->getAll();

        return response_json(['document' => DocumentResource::collection($documents)]);
    }

    /**
     * Получение документа
     *
     * Права: `crud document`
     *
     * @group Документы
     *
     * @urlParam document integer required
     *
     * @param  Document  $document
     * @return JsonResponse
     */
    public function show(Document $document): JsonResponse
    {
        return response_json(['document' => DocumentResource::make($document)]);
    }

    /**
     * Добавление документа
     *
     * Права: `crud document`
     *
     * @group Документы
     *
     * @param  StoreRequest  $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $document = $this->documentService->store($request->validated());

        return response_json(['document' => DocumentResource::make($document)]);
    }

    /**
     * Обновление документа
     *
     * Права: `crud document`
     *
     * @group Документы
     *
     * @param  UpdateRequest  $request
     * @param  int  $documentId
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $documentId): JsonResponse
    {
        $document = $this->documentService->update($documentId, $request->validated());

        return response_json(['document' => DocumentResource::make($document)]);
    }

    /**
     * Удаление документа
     *
     * Права: `crud document`
     *
     * Удаление только если нет связанных таблиц
     *
     * @group Документы
     *
     * @urlParam document integer required
     *
     * @param  int  $documentId
     * @return JsonResponse
     * @throws UsedInOtherTableException
     */
    public function destroy(int $documentId): JsonResponse
    {
        $this->documentService->delete($documentId);

        return response_success();
    }

    /**
     * Генерация документа
     *
     * Права: `crud document`
     *
     * @group Документы
     *
     * @urlParam order integer required
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     * @throws CustomValidationException
     */
    public function generate(Request $request, Order $order): JsonResponse
    {

        if (!data_get($order, 'id')) {
            throw new CustomValidationException(
                'Заказ не найден', 422
            );
        }

        $result = (new DocumentGenerateService)->generate($request, $order);

        return response_json([
            'pdf' => $result
        ]);
    }

    /**
     * Получение ссылки на документ
     *
     * Права: `crud document`
     *
     * @group Документы
     *
     * @urlParam document integer required
     *
     * @param Document $document
     * @return JsonResponse
     * @throws CustomValidationException
     */
    public function generateUrl(Document $document): JsonResponse
    {

        if (!data_get($document, 'id')) {
            throw new CustomValidationException(
                'Документ не найден', 422
            );
        }

        if (!$document->order_id && !$document->document_template_id) {
            throw new CustomValidationException(
                'Документ сломан. Не хватает данных', 422
            );
        }

        return response_json([
            'url' => asset(sprintf('storage/documents/pdf/%s-%s.pdf', $document->order_id, $document->document_template_id))
        ]);

    }

    /**
     * Доступные параметры для документов
     * @group Документы
     * {
    "id": 1,
    "name": "Документ 1",
    "comments": "Документ 1",
    "order": {

    "id": 2,
    "user": {
    "id": 1,
    "name": "Петр",
    "surname": "Леонидов",
    "middle_name": "Иванович",
    "office_position": "voluptatem",
    "is_active": true,
    "avatar": null,
    "department_id": null
    },
    "client": {
    "id": 1,
    "name": "Клара",
    "surname": "Кошелева",
    "middle_name": null,
    "born_at": "21.08.1959",
    "notes": "Corrupti dolorem reprehenderit excepturi qui.",
    "address": "Libero dignissimos illum incidunt voluptatem.",
    "passport": "A consequuntur necessitatibus praesentium ex.",
    "phones": [
    "(495) 012-7061",
    "(812) 747-74-44",
    "(812) 584-38-95"
    ],
    "gender": "female",
    "emails": [

    ],
    "created_at": "11.07.2022 11:36",
    "updated_at": "11.07.2022 11:36",
    "department_id": 4,
    "city_id": 40
    },
    "appeal_reason": {
    "id": 1,
    "name": "Апеал резонс",
    "created_at": "18.07.2022 16:12",
    "updated_at": "18.07.2022 16:12"
    },
    "car": {
    "id": 1,
    "number": "A782KQ98KZ",
    "vin": "ASS74990273829835",
    "year": 1991,
    "body": "Седан",
    "color": "Сиена жжёная",
    "notes": null,
    "created_at": "11.07.2022 11:36",
    "updated_at": "11.07.2022 11:36",
    "fuel": {
    "id": 4,
    "name": "Другое"
    },
    "engine_volume": null,
    "car_model": {
    "id": 5,
    "name": "Non 506",
    "car_mark": {
    "id": 4,
    "name": "Aston Martin"
    }
    }
    },
    "pipeline": null,
    "stage": null,
    "process_category": null,
    "department": {
    "id": 1,
    "name": "non / est",
    "slug": "harum-qui-culpa-harum-tempora-voluptate-"
    },
    "created_at": "18.07.2022 14:11",
    "updated_at": "18.07.2022 14:11"
    },
    "document_template": {
    "id": 4,
    "name": "Шаблон 11",
    "created_at": "20.07.2022 16:36",
    "updated_at": "22.07.2022 13:07"
    },
    "created_at": "20.07.2022 17:27",
    "updated_at": "20.07.2022 17:28"
    }
     */
}
