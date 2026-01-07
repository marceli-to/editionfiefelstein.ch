<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_product';

    protected $fillable = [
      'order_id',
      'product_id',
      'title',
      'isbn',
      'image',
      'quantity',
      'price',
      'shipping',
    ];

    public function order()
    {
      return $this->belongsTo(Order::class);
    }

    public function product()
    {
      return $this->belongsTo(Product::class);
    }
}
