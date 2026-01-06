<?php
namespace App\Enums;
enum ProductState: string
{
  case Deliverable = 'deliverable';
  case NotAvailable = 'not_available';

  public function label(): string
  {
    return match($this) {
      self::Deliverable => 'lieferbar',
      self::NotAvailable => 'nicht verfügbar',
    };
  }

  public function value(): string
  {
    return match($this) {
      self::Deliverable => 'deliverable',
      self::NotAvailable => 'not_available',
    };
  }
}