<main class="grid grid-cols-12 lg:gap-x-16 px-16">

  <div class="col-span-6 lg:col-span-2 order-1 sticky top-0 z-70 bg-white lg:bg-transparent max-h-screen">
    <x-icons.logo class="max-w-160 lg:max-w-220 mt-20" />
  </div>

  <div class="col-span-12 lg:col-span-8 order-3 lg:order-2 pb-32">
    {{ $slot }}
  </div>

  <div class="col-span-6 lg:col-span-2 order-2 lg:order-3 sticky top-0 z-70 bg-white pb-20 lg:bg-transparent max-h-screen">

    <div class="flex items-end lg:items-start flex-col lg:flex-row lg:grid lg:grid-cols-2 lg:gap-x-16 relative">

      <div class="mt-20 lg:mt-75 lg:col-span-1 order-2 lg:order-1">
        <x-menu.button />
      </div>

      @if (!Route::is('order.*'))
        <div class="mt-20 order-1 lg:order-2 lg:col-span-1 lg:mt-75 relative lg:flex lg:justify-end lg:z-80">
          <livewire:cart-icon />
        </div>
      @endif
    </div>

      @if (!Route::is('order.*'))
        <livewire:cart />
      @endif

      <x-menu.wrapper />
  </div>
</main>


