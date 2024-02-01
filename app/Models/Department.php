<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'name',
    ];
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
