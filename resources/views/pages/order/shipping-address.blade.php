@extends('app')
@section('content')
@if ($errors->any())
  <x-form.errors>
    Bitte füllen Sie alle Pflichtfelder aus.
  </x-form.errors>
@else
  <x-layout.page-title>
    Lieferadresse
  </x-layout.page-title>
@endif
<div class="md:grid md:grid-cols-8 gap-x-16">
  <div class="hidden md:block md:col-span-2">
    <x-order.menu order_step="{{ $order_step }}" />
  </div>
  <div class="md:col-span-6 lg:col-span-4" x-data="{
    useInvoiceAddress: {{ ($cart['shipping_address']['use_invoice_address'] ?? old('use_invoice_address')) ? 'true' : 'false' }},
    invoice: {
      firstname: '{{ $cart['invoice_address']['firstname'] ?? '' }}',
      name: '{{ $cart['invoice_address']['name'] ?? '' }}',
      company: '{{ $cart['invoice_address']['company'] ?? '' }}',
      street: '{{ $cart['invoice_address']['street'] ?? '' }}',
      street_number: '{{ $cart['invoice_address']['street_number'] ?? '' }}',
      zip: '{{ $cart['invoice_address']['zip'] ?? '' }}',
      city: '{{ $cart['invoice_address']['city'] ?? '' }}',
      country: '{{ $cart['invoice_address']['country'] ?? '' }}'
    },
    shipping: {
      firstname: '{{ $cart['shipping_address']['firstname'] ?? old('firstname') ?? '' }}',
      name: '{{ $cart['shipping_address']['name'] ?? old('name') ?? '' }}',
      company: '{{ $cart['shipping_address']['company'] ?? old('company') ?? '' }}',
      street: '{{ $cart['shipping_address']['street'] ?? old('street') ?? '' }}',
      street_number: '{{ $cart['shipping_address']['street_number'] ?? old('street_number') ?? '' }}',
      zip: '{{ $cart['shipping_address']['zip'] ?? old('zip') ?? '' }}',
      city: '{{ $cart['shipping_address']['city'] ?? old('city') ?? '' }}',
      country: '{{ $cart['shipping_address']['country'] ?? old('country') ?? '' }}'
    }
  }">
    <form method="POST" action="{{ route('order.shipping-address-store') }}">
      @csrf
      <div class="space-y-1">

        @if($can_use_invoice_address)
          <x-table.row class="border-b border-b-black !min-h-34">
            <x-form.checkbox
              name="use_invoice_address"
              value="1"
              label="Die Lieferadresse entspricht der Rechnungsadresse"
              checked="{{ $cart['shipping_address']['use_invoice_address'] ?? old('use_invoice_address') }}"
              x-model="useInvoiceAddress" />
          </x-table.row>
        @endif

        <div :class="{ 'opacity-50 pointer-events-none': useInvoiceAddress }">
          <x-table.row class="{{ $can_use_invoice_address ? '!mt-32' : '' }}">
            <x-form.input
              name="firstname"
              placeholder="Vorname"
              required="true"
              x-bind:value="useInvoiceAddress ? invoice.firstname : shipping.firstname"
              x-bind:disabled="useInvoiceAddress" />
          </x-table.row>
          <x-table.row>
            <x-form.input
              name="name"
              placeholder="Nachname"
              required="true"
              x-bind:value="useInvoiceAddress ? invoice.name : shipping.name"
              x-bind:disabled="useInvoiceAddress" />
          </x-table.row>
          <x-table.row>
            <x-form.input
              name="company"
              placeholder="Firma"
              x-bind:value="useInvoiceAddress ? invoice.company : shipping.company"
              x-bind:disabled="useInvoiceAddress" />
          </x-table.row>
          <x-table.row>
            <x-form.input
              name="street"
              placeholder="Strasse"
              required="true"
              x-bind:value="useInvoiceAddress ? invoice.street : shipping.street"
              x-bind:disabled="useInvoiceAddress" />
          </x-table.row>
          <x-table.row>
            <x-form.input
              name="street_number"
              placeholder="Hausnummer"
              x-bind:value="useInvoiceAddress ? invoice.street_number : shipping.street_number"
              x-bind:disabled="useInvoiceAddress" />
          </x-table.row>
          <x-table.row>
            <x-form.input
              name="zip"
              placeholder="PLZ"
              required="true"
              x-bind:value="useInvoiceAddress ? invoice.zip : shipping.zip"
              x-bind:disabled="useInvoiceAddress" />
          </x-table.row>
          <x-table.row>
            <x-form.input
              name="city"
              placeholder="Ort"
              required="true"
              x-bind:value="useInvoiceAddress ? invoice.city : shipping.city"
              x-bind:disabled="useInvoiceAddress" />
          </x-table.row>
          <x-table.row class="border-b border-b-black">
            <select
              name="country"
              x-bind:disabled="useInvoiceAddress"
              class="w-full relative bg-[url('../icons/chevron-down-tiny.svg')] bg-[length:11px_auto] bg-[right_center] border-0 focus:ring-0 text-sm color-black placeholder:text-black p-0 appearance-none disabled:bg-transparent">
              @foreach(config('countries.delivery') as $option)
                <option
                  value="{{ $option }}"
                  x-bind:selected="(useInvoiceAddress ? invoice.country : shipping.country) === '{{ $option }}'">
                  {{ $option }}
                </option>
              @endforeach
            </select>
          </x-table.row>
        </div>
      </div>
      <x-table.row class="border-none mt-32">
        <x-buttons.primary label="Zahlung" type="button" />
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