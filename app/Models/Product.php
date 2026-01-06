<?php
namespace App\Models;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Enums\ProductState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
  use SoftDeletes, HasSlug;

  protected $fillable = [
    'uuid',
    'title',
    'isbn',
    'price',
    'shipping',
    'stock',
    'attributes',
    'rows',
    'image',
    'publish',
    'state',
    'sort',
    'user_id',
  ];

  protected $casts = [
    'publish' => 'boolean',
    'price' => 'decimal:2',
    'shipping' => 'decimal:2',
    'attributes' => 'array',
    'rows' => 'array',
    'state' => ProductState::class,
  ];

  protected $appends = [
    'stateText',
  ];

  /**
   * Get the options for generating the slug.
   */
  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
  }

  /**
   * Get the indexable data array for the model.
   *
   * @return array<string, mixed>
   */
  public function toSearchableArray(): array
  {
    return [
      'title' => $this->title,
      'isbn' => $this->isbn,
      'publish' => $this->publish,
    ];
  }
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function scopePublished($query)
  {
    return $query->where('publish', true);
  }

  public function getRouteKeyName(): string
  {
    return 'slug';
  }

  public function getStateTextAttribute(): string
  {
    if ($this->state->value == 'not_available') {
      return 'Derzeit nicht verfügbar';
    }

    return $this->state->label();
  }
}
