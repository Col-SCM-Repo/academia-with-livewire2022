<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    protected $fillable = [
        'id',
        'code',
        'type',
        'student_id',
        'classroom_id',
        'user_id',
        'career_id',
        'paymennt_type',
        'fees_quantity',
        'period_cost',
        'cancelled',
        'observations'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function installments()
    {
        return $this->hasMany(installments::class)->where('state', null);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function balance()
    {
        $total = 0;
        foreach ($this->installments->where('type', 'installment') as $key => $installment) {
            $total += $installment->balance();
        }

        return $total;
    }

    public function payment_status_descripion()
    {
        if ($this->balance() < $this->period_cost) {
            return 'pending';
        } else {
            return 'finished';
        }
    }



    public function status_descripion()
    {
        $current_date = date('Y-m-d');
        if ($this->cancelled == 1) {
            return 'Retirado';
        }
        return $current_date <= $this->classroom->level->end_date ? 'Vigente' : 'Concluida';
    }

    public function incidentes()
    {
        return $this->hasMany(Incidente::class, 'enrollment_id', 'id');
    }

    public function estado_incidentes()
    {
        $incidentes = $this->incidentes();
        foreach ($incidentes as  $incidente)
            if ($incidente->estado == "pendiente")
                return "pendiente";
        return "no pendiente";
    }
}
