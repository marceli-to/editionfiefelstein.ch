<body
  class="antialiased font-europa-regular text-sm tracking-[0.02em] min-h-screen"
  x-data="{ menu: false, cart: false }"
  @toggle-cart.window="cart = !cart; menu = false"
  @display-updated-cart.window="cart = true; menu = false"
  @hide-updated-cart.window="cart = false">
  {{-- <x-debug /> --}}
  {{ $slot }}
</body>
