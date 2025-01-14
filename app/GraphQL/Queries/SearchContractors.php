<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Contractor;
use App\Services\ElasticManager;
use Illuminate\Support\Arr;

final readonly class SearchContractors
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $skill = Arr::get($args, 'skill');
        $queries = [];
        foreach ($skill as $s) {
            $queries[] = [
                'term' => [
                    'skills' => $s
                ]
            ];
        }
        $result = app(ElasticManager::class)->executeQuery([
            'query' => [
                'bool' => [
                    'must' => $queries
                ]
            ]
        ]);
        $data = collect(Arr::get($result, 'results', []))->map(fn($r) => Contractor::find($r['id']));

        return [
            'count' => Arr::get($result, 'count', 0),
            'contractors' => $data
        ];
    }
}
