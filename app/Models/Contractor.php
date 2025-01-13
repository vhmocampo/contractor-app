<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contractor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ca_contractors';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The contractor's skills
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'ca_contractors_skills', 'contractor_id', 'skill_id')->using(ContractorSkill::class);
    }
}
