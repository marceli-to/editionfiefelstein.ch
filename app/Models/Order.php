<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'uuid',
    'salutation',
    'firstname',
    'name',
    'email',
    'company',
    'street',
    'street_number',
    'zip',
    'city',
    'country',
    'use_invoice_address',
    'shipping_company',
    'shipping_firstname',
    'shipping_name',
    'shipping_street',
    'shipping_street_number',
    'shipping_zip',
    'shipping_city',
    'shipping_country',
    'total',
    'payment_method',
    'payed_at',
  ];

  protected $appends = [
    'invoice_name', 
    'invoice_company',
    'invoice_address', 
    'invoice_location',
    //'shipping_name',
    'shipping_fullname',
    'shipping_address',
    'shipping_location',
    'payment_info',
    'order_number',
  ];

  // Add a relation ship to order_product
  public function orderProducts()
  {
    return $this->hasMany(OrderProduct::class);
  }

  public function products()
  {
    return $this->belongsToMany(Product::class, 'order_product')->withPivot(
      'title',
      'description',
      'price',
      'quantity'
    );
  }

  public function getInvoiceNameAttribute()
  { 
    return $this->firstname . ' ' . $this->name;
  }

  public function getInvoiceCompanyAttribute()
  {
    return $this->company ? $this->company : null;
  }

  public function getInvoiceAddressAttribute()
  {
    return $this->street . ' ' . $this->street_number;
  }

  public function getInvoiceLocationAttribute()
  {
    return $this->zip . ' ' . $this->city;
  }

  public function getShippingFullNameAttribute()
  {
    return $this->shipping_firstname . ' ' . $this->shipping_name;
  }

  public function getShippingAddressAttribute()
  {
    return $this->shipping_street . ' ' . $this->shipping_street_number;
  }

  public function getShippingLocationAttribute()
  {
    return $this->shipping_zip . ' ' . $this->shipping_city;
  }

  public function getPaymentInfoAttribute()
  {
    return 'Kreditkarte / ' . \Carbon\Carbon::parse($this->paid_at)->format('d.m.Y, H:i');
    //return $this->payment_method . ' / ' . \Carbon\Carbon::parse($this->paid_at)->format('d.m.Y, H:i');
  }

  /**
   * Order Number is the ID of the order with a prefix and filled with leading zeros (6 digits)
   */
  public function getOrderNumberAttribute()
  {
    return 'FS-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
  }
}
