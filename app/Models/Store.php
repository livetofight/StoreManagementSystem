<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stores';
    protected $fillable = [
        'name',
        'owner_id',
        'address',
        'created_at',
        'updated_at',
    ];

    public function listProduct()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }
}
