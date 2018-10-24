<?php

namespace PagarMe\Sdk\Customer\Document;

trait DocumentBuilder
{
    /**
     * @param array $DocumentData
     * @return DocumentCollection
     */
    private function buildDocuments($DocumentData)
    {
        $documents = new DocumentCollection();

        if (is_array($DocumentData)) {
            foreach ($DocumentData as $document) {
                $documents[] = new Document(!is_array($document) ? get_object_vars($document) : $document);
            }
        }

        return $documents;
    }
}
