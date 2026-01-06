<div
  x-cloak 
  x-show="menu"
  x-transition:enter="transition ease-in duration-100"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-0"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed left-0 top-145 lg:top-0 bg-white bg-opacity-95 w-full h-[calc(100vh_-_145px)] lg:h-full z-60">
  <div class="px-16 lg:grid lg:grid-cols-12 lg:gap-x-16">
    <ul class="text-lg -mt-10 lg:mt-90 lg:col-span-5 lg:col-start-3">
      <li>
        <x-menu.item title="Produkte" :url="route('home')" />
      </li>
      <li class="my-6">
        <x-menu.item title="Kontakt" :url="route('contact')" :current="request()->routeIs('contact')" />
      </li>
    </ul>
  </div>
</div>