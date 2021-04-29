<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Share;
use App\Models\Product;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'product_id',
        'quantity'
    ];

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }    
}
