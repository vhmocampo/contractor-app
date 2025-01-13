<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Job extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ca_jobs';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Each job has a location
     *
     * @return HasOne
     */
    public function location() {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    /**
     * Each job has a customer as well
     *
     * @return HasOne
     */
    public function customer() {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    /**
     * Each job requires multiple skill sets
     *
     * @return BelongsToMany
     */
    public function skillsRequired() {
        return $this->belongsToMany(Skill::class, 'ca_jobs_skills', 'job_id', 'skill_id');
    }
}
