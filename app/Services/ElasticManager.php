<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Elasticsearch;

class ElasticManager {

    /**
     * Execute a search query on the primary index
     *
     * @param array $query
     * @return array
     */
    public function executeQuery($query)
    {
        $params = [
            'index' => env('ELASTICSEARCH_INDEX_NAME', 'contractorapp'),
            'body'  => $query
        ];

        return $this->formatQueryResults(Elasticsearch::search($params));
    }

    /**
     * Index specific entity, must be Eloquent model with valid transformer
     *
     * @param Model $e
     * @return string
     */
    public function indexEntity(Model $e)
    {
        $class = class_basename($e);
        $transformer = sprintf("App\\Transformers\\%sTransformer", $class);

        $transformedData = fractal($e, $transformer)->toArray();
        $docId = sprintf("%s:%d", $class, $e->id);

        $body = array_merge([
            'id' => $e->id,
            'doc_type' => $class,
        ], Arr::get($transformedData, 'data'));

        $response = Elasticsearch::index(
            $this->formatBodyForIndexing($docId, $body)
        );

        return Arr::get($response, 'result');
    }

    /**
     * Return entity matching a given Eloquent model
     *
     * @param Model $e
     * @return array
     */
    public function getEntity(Model $e)
    {
        $class = class_basename($e);
        $esId = sprintf("%s:%d", $class, $e->id);

        return Elasticsearch::get(
            $this->formatBodyForFetching([
                'id' => $esId
            ])
        );
    }

    /**
     * Format elasticearch query results
     *
     * @param array $results
     * @return array
     */
    public function formatQueryResults($results)
    {
        return [
            'results' => collect(Arr::get($results, 'hits.hits'))->map(function($d) {
                return Arr::get($d, '_source');
            })->all(),
            'count' => Arr::get($results, 'hits.total.value'),
            'count_operator' => Arr::get($results, 'hits.total.relation'),
        ];
    }

    /**
     * Format elasticsearch query to index entity
     *
     * @param string $docId
     * @param array $body
     * @return array
     */
    public function formatBodyForIndexing($docId, $body)
    {
        $data = [
            'id' => $docId,
            'body' => $body,
            'index' => env('ELASTICSEARCH_INDEX_NAME', 'contractorapp'),
        ];

        return $data;
    }

    /**
     * Format elasticsearch query to read entity
     *
     * @param array $body
     * @return array
     */
    public function formatBodyForFetching($body)
    {
        $params = [
            'id'    => Arr::get($body, 'id'),
            'index' => env('ELASTICSEARCH_INDEX_NAME', 'contractorapp'),
        ];

        return $params;
    }
}