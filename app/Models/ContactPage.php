<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
  protected $table = 'contact_page';

  protected $fillable = [
    'imprint', 
    'toc_title',
    'toc_items',
    'privacy'
  ];

  protected $casts = [
    'toc_items' => 'array'
  ];
}
