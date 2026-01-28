@extends('app')
@section('content')
<x-layout.page-title>
  @if ($cart['items'])
    Warenkorb
  @else
    Ihr Warenkorb ist leer
  @endif
</x-layout.page-title>
<div class="md:grid md:grid-cols-8 gap-x-16">
  <div class="hidden md:block md:col-span-2">
    <x-order.menu order_step="{{ $order_step }}" />
  </div>
  @if ($cart['items'])
    <div class="md:col-span-6 lg:col-span-4">
      @foreach($cart['items'] as $item)
        <livewire:cart-item :uuid="$item['uuid']" :key="$item['uuid']" />
      @endforeach
      <livewire:cart-total />
      <x-table.row class="border-none">
        <x-buttons.primary route="{{ route('order.invoice-address') }}" label="Rechnungsadresse" class="!min-h-34" />
      </x-table.row>
    </div>
  @endif
  <div class="hidden lg:block lg:col-span-2">
    @foreach($cart['items'] as $item)
      <x-media.image :src="$item['image']" :alt="$item['title']" class="hidden md:block md:mb-16" />
    @endforeach
  </div>
</div>
@endsection