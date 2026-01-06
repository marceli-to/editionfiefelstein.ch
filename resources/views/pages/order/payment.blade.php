@extends('app')
@section('content')
@if ($errors->any())
  <x-form.errors>
    Bitte Zahlungsmethode auswählen.
  </x-form.errors>
@else
  <x-layout.page-title>
    Zahlung
  </x-layout.page-title>
@endif
<div class="md:grid md:grid-cols-8 gap-x-16">
  <div class="hidden md:block md:col-span-2">
    <x-order.menu order_step="{{ $order_step }}" />
  </div>
  <div class="md:col-span-6 lg:col-span-4">
    <form method="POST" action="{{ route('order.payment-method-store') }}">
      @csrf
      <x-table.row class="border-b border-b-black min-h-34">
        <span>Zahlungsmittel</span>
      </x-table.row>
      <x-table.row class="!min-h-64 !mt-1 flex items-center !border-t-0">
        <x-form.radio 
          name="payment_method" 
          value="credit_card" 
          checked="true">
          <div class="flex gap-x-16">
            Kreditkarte
          </div>
        </x-form.radio>
      </x-table.row>
      <x-table.row class="border-none mt-1">
        <x-buttons.primary label="Weiter" type="button" class="!min-h-33" />
      </x-table.row>
    </form>
  </div>
  <div class="hidden lg:block lg:col-span-2">
    @foreach($cart['items'] as $item)
      <x-media.image :src="$item['image']" :alt="$item['title']" class="hidden md:block md:mb-16" />
    @endforeach
  </div>
</div>
@endsection