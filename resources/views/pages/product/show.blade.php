@extends('app')
@section('content')
<x-layout.page-title>
  Produkte: {{ $product->title }}
</x-layout.page-title>
<div
  class="relative lg:mt-30"
  x-data="{ shippingInfo: false }">
  <div
    class="hidden lg:block absolute inset-0 z-20 m-32 left-[calc((100%_/_4))] top-0 w-[calc((100%/2)_-_64px)] h-[calc(100vh_-_310px)]"
    x-cloak
    x-show="shippingInfo"
    x-on:click.outside="shippingInfo = false"
    x-on:keyup.escape.window="shippingInfo = false">
    <div class="bg-flame font-europa-regular font-regular text-white text-lg w-full h-full p-22 pr-64 relative">
      <a
        href="javascript:;"
        x-on:click="shippingInfo = !shippingInfo"
        class="absolute right-32 top-32"
        title="Versandinstruktionen verbergen">
        <x-icons.cross size="lg" />
      </a>
    </div>
  </div>

  @if ($product->image)
    <x-media.image :src="$product->image" :alt="$product->title" />
  @endif

  <div class="md:grid md:grid-cols-12 md:gap-x-16 lg:block">
    <div class="md:col-span-10 md:col-start-2">
      <x-product.info :product="$product" />
    </div>
  </div>

</div>
@endsection