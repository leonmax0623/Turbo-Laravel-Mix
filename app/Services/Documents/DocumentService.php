<?php

namespace App\Services\Documents;

use App\Exceptions\UsedInOtherTableException;
use Illuminate\Support\Collection;
use App\Models\Document;
use Throwable;

class DocumentService
{
    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Document::orderBy('id', 'desc')->get();
    }

    /**
     * @param  array  $data
     * @return Document
     */
    public function store(array $data): Document
    {
        return Document::create($data);
    }

    /**
     * @param  int  $documentId
     * @return Document
     */
    public function getDocumentTemplateById(int $documentId): Document
    {
        return Document::findOrFail($documentId);
    }

    /**
     * @param  int  $documentId
     * @param  array  $data
     * @return Document
     */
    public function update(int $documentId, array $data): Document
    {
        $document = $this->getDocumentTemplateById($documentId);
        $document->update($data);

        return $document;
    }

    /**
     * @param  int  $documentId
     * @throws UsedInOtherTableException
     */
    public function delete(int $documentId): void
    {
        try {
            Document::where('id', $documentId)->delete();
        } catch (Throwable) {
            throw new UsedInOtherTableException(
                'Документ нельзя удалить, так как он уже используется в других таблицах', 422
            );
        }
    }
}
