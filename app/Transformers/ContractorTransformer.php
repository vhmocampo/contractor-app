<?php

namespace App\Transformers;

use App\Models\Contractor;
use League\Fractal\TransformerAbstract;

class ContractorTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'first_name',
        'last_name',
        'skills'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Contractor $data)
    {
        return [
            'description' => $data->first_name . " " . $data->last_name,
            'yoe' => $data->yoe ?? 0,
            'skills' => $data->skills->map(fn($s) => $s->name)->all()
        ];
    }
}
