<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    use HasFactory;

    protected $fillable = ['$name', '$description'];

    public function file()
    {
        return $this->hasMany(File::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_foods');
    }
}
