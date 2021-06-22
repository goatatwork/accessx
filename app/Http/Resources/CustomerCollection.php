<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collectionAsArray($request),
            'paginated' => $this->collectionAsChunkedArray($request),
            'paginator_meta' => [
                'page' => $request->page,
                'total_pages' => count($this->collectionAsChunkedArray($request)),
                'records_per_page' => $request->records_per_page,
                'total_records' => count($this->collectionAsArray($request)),
            ],
            'sort_key' => $request->sort_key,
            'sort_order' => $request->sort_order
        ];
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function collectionAsArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Transform the resource collection into a chunked array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function collectionAsChunkedArray(Request $request)
    {
        $chunk_size = $request->records_per_page ? $request->records_per_page : 25;

        return parent::chunk($chunk_size);
    }
}
