<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ca_contracts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Each contract has a job
     *
     * @return HasOne
     */
    public function job() {
        return $this->hasOne(Job::class, 'id', 'job_id');
    }

    /**
     * Each contract has a contractor
     *
     * @return HasOne
     */
    public function contractor() {
        return $this->hasOne(Contractor::class, 'id', 'contractor_id');
    }
}
