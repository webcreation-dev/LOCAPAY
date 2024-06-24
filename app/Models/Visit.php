<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'visit_date',
        'manager_id',
        'user_id'
    ];

    const STATUS = [
        'waiting',
        'in_progress',
        'finished'
    ];

    public function propertie()
    {
        return $this->belongsTo(Property::class);
    }

    public function visitProperties()
    {
        return $this->hasMany(VisitProperty::class, 'visit_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
