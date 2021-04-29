<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Share;

class Waybill extends Model
{
    use HasFactory;

    protected $fillable = [
        'share_id',
        'date',
        'quantity',
        'bags',
        'driver',
        'phone',
        'truck'
    ];

    public function share()
    {
        return $this->belongsTo(Share::class);
    } 
}
