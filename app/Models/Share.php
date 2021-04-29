<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Allocation;
use App\Models\Plant;
use App\Models\Waybill;


class Share extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_id',
        'plant_id',
        'quantity',
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function waybills()
    {
        return $this->hasMany(Waybill::class);
    }
}
