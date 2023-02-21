<?php

namespace App\Services\Documents;

use App\Exceptions\CustomValidationException;
use App\Http\Resources\Documents\Generate\GenerateDocumentResource;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\Order;
use App\Traits\LogTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Throwable;

class DocumentGenerateService
{
    use LogTrait;

    /**
     * @throws CustomValidationException
     */
    public function generate(Request $request, Order $order): array
    {
        try {
            /** @var Document $document */

            $result = [];

            $templates = [
                [
                    'key' => 'documents.order', // заказ наряд
                    'name' => 'Документ заказ-наряда',
                ],
                [
                    'key' => 'documents.completion', // акт об оказании услуг
                    'name' => 'Акт об оказании услуг',
                ],
//                [
//                    'key' => 'documents.reception', // акт сдачи
//                    'name' => 'Акт сдачи',
//                ],
                [
                    'key' => 'documents.certificate', // акт приёма
                    'name' => 'Приёмный акт',
                ],
            ];

            foreach ($templates as $template) {

                $documentTemplate = DocumentTemplate::query()
                    ->where('template', data_get($template, 'key'))
                    ->firstOrCreate([
                        'name' => data_get($template, 'name'),
                        'template' => data_get($template, 'key'),
                    ]);

                $document = Document::query()->updateOrCreate([
                    'name' => sprintf('Документ заказа № %s', $order->id),
                    'document_template_id' => $documentTemplate->id,
                    'order_id' => $order->id,
                ]);

                $data = GenerateDocumentResource::make($document)->toArray($request);

                $result[] = [
                    'name' => data_get($template, 'name'),
                    'link' => $this->htmlToPdf(data_get($template, 'key'), compact('data'), $documentTemplate, $order)
                ];
            }

            return $result;

        } catch (Throwable $e) {
            $this->error($e, 'Ошибка при генерации документа');
        }

        throw new CustomValidationException(
            'Извините нам очень жаль, случилась ошибка при генерации документа. Обратитесь в службу поддержки', 422
        );
    }

    /**
     * @param string $name
     * @param array $data
     * @param DocumentTemplate $documentTemplate
     * @param Order $order
     * @return string
     */

    public function htmlToPdf(string $name, array $data, DocumentTemplate $documentTemplate, Order $order): string
    {
        $now = now();
        $view = explode('.', $name);
        $dir = storage_path('app/public/documents/pdf');
        $path = sprintf('%s/%s-%s-%s.pdf', $dir, data_get($view, '1', $now), $order->id, $documentTemplate->id);

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true, true);
        }

        PDF::loadView($name, $data)
            ->save($path);

        return asset(sprintf('storage/documents/pdf/%s-%s-%s.pdf', data_get($view, '1', $now), $order->id, $documentTemplate->id));
    }

    //    public function generateOld(Request $request, Order $order): array
//    {
//        try {
//            $result = [];
//
//            $documentTemplates = DocumentTemplate::query()->get();
//
//            foreach ($documentTemplates as $documentTemplate) {
//                $result[] = $this->documentCollect($request, $order, $documentTemplate);
//            }
//
//            return $result;
//
//        } catch (Throwable $e) {
//            $this->error($e,'Ошибка при генерации документа');
//        }
//
//        throw new CustomValidationException(
//            'Извините нам очень жаль, случилась ошибка при генерации документа. Обратитесь в службу поддержки', 422
//        );
//    }
//
//    private function documentCollect($request, $order, DocumentTemplate $documentTemplate): string
//    {
//
//        $document = Document::updateOrCreate([
//            'name'                  => sprintf('Документ заказа № %s', $order->id),
//            'document_template_id'  => $documentTemplate->id,
//            'order_id'              => $order->id,
//        ]);
//
//        $data = DocumentResource::make($document)->toArray($request);
//
//        $template = data_get($data, 'document_template.template');
//
//        preg_match_all('/\$[^$]+\$/', $template, $properties);
//
//        $html = $this->getByProperties(data_get($properties, '0', []), $data);
//
//        return  $this->htmlToPdf($html, $documentTemplate, $order);
//    }
//
//    private function getByProperties(array $properties, array $data): string|null
//    {
//        $message = data_get($data, 'document_template.template', '');
//
//        foreach ($properties as $property) {
//            $value = data_get($data, str_replace('$', '', $property), '');
//
//            if (gettype($value) === 'array') {
//                $value = implode(', ', $value);
//            }
//
//            $message = str_replace($property, $value, $message);
//        }
//
//        return $message;
//
//    }

}
