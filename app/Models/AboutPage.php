<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
  protected $table = 'about_page';

  protected $fillable = [
    'quote',
    'quote_author',
    'text'
  ];
}
