<?php

namespace App\Models;

use App\Enums\TipoDescuentosEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    protected $table = 'scholarships';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'type_scholarship',
        'description',
        'parameter_discount',
    ];

    public function typeScholarship()
    {
        return TipoDescuentosEnum::getName($this->type_scholarship) ;
    }

}
