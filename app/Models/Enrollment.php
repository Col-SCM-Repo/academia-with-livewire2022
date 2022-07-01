<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    //
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    public function installments()
    {
        return $this->hasMany('App\Installment')->where('state', null);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function career()
    {
        return $this->belongsTo('App\Career');
    }

    public function relative()
    {
        return $this->belongsTo('App\Relative');
    }

    public function classroom()
    {
        return $this->belongsTo('App\Classroom');
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
    protected $fillable = [
        'id',
        'code',
        'type',
        'student_id',
        'classroom_id',
        'relative_id',
        'relative_relationship',
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
