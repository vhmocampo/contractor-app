<?php

namespace App\Models\Traits;

use App\Services\ElasticManager;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait to define searchable entities in the ES instance
 */
trait Searchable
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Model $model) {
            $model->commitToIndex();
        });

        static::updated(function (Model $model) {
            $model->commitToIndex();
        });
    }

    /**
     * Commit an entity (model) to primary index. If exists, will update existing document
     *
     * @return void
     */
    public function commitToIndex()
    {
        $em = app(ElasticManager::class);
        try {
            $em->indexEntity($this);
        } catch (\Exception $e) {
            logger()->error('Unable to commit entity to index ... ' . $e->getMessage());
        }
    }
}
