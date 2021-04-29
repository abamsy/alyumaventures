<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Share;


class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'contact_person',
        'phone'
    ];

    public function shares()
    {
        return $this->hasMany(Share::class);
    } 
}
