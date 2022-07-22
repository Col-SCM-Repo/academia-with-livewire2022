<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use SoftDeletes;
    protected $table = "entities";
    protected $primariKey = "id";

    protected $fillable = [
        'father_lastname',
        'mother_lastname',
        'name',
        'address',
        'district_id',
        'telephone',
        'mobile_phone',
        'email',
        'birth_date',
        'gender',
        'country_id',
        'document_type',
        'document_number',
        'marital_status',
        'instruction_degree',
        'photo_path',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'entity_id', 'id');
    }

    public function relative()
    {
        return $this->hasOne(Relative::class, 'entity_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function full_name()
    {
        return $this->father_lastname . ' ' . $this->mother_lastname . ', ' . $this->name;
    }
}
