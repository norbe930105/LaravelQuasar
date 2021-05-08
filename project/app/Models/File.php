<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['$nombre', '$extension', '$tamanno'];

    public function foods()
    {
        return $this->belongsTo(Foods::class);
    }
}
