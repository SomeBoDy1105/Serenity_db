<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;
    protected $table = 'specialties';
    protected $guarded = ['id'];

    public function advisers()
    {
        return $this->hasMany(Adviser::class, 'id_specialty');
    }
}
