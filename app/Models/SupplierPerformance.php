<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPerformance extends Model
{
    use HasFactory;

    protected $table = 'supplier_performance';

    protected $fillable = [
        'month',
        'year',
        'supplier_id',
        'product_defect',
        'delivery',
        'cost',
        'return_time',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
