<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ca_skills';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public static $defaultValues = [
        "Welding",
        "Carpentry",
        "Woodworking",
        "Electrical",
        "Sanitary",
        "Plumbing",
        "General Construction",
        "Landscaping",
        "Masonry",
        "Finish carpentry",
        "Framing",
        "Roofing",
        "Concrete forming",
        "Electrical wiring",
        "Pipe fitting",
        "Demolition",
        "Drywall Installation / Finishing",
    ];
}
