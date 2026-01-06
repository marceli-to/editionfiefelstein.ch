@extends('app')
@section('content')
<section class="lg:grid lg:grid-cols-8 lg:gap-x-16">
  <div class="lg:col-span-4">
    <x-table.row class="font-europa-bold font-bold py-4 !min-h-33">
      {{ $product->title }}
    </x-table.row>
    @foreach($product->attributes as $attribute)
      <x-table.row class="py-4 !min-h-33">
        {{ $attribute }}
      </x-table.row>
    @endforeach
    <x-table.row class="py-4 !min-h-33">
      CHF {{ $product->price }}
    </x-table.row>
    
    @if ($product->stock > 0 && $product->state->value() == 'deliverable')
      <livewire:cart-button :productUuid="$product->uuid" :key="$product->uuid" />
    @endif


  </div>
</section>
@endsection