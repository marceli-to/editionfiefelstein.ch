<div
  x-cloak
  x-show="menu"
  x-transition:enter="transition ease-in duration-100"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-0"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed lg:absolute inset-0 lg:left-0 top-86 lg:top-100 bg-white bg-opacity-95 lg:bg-transparent z-60">
  <ul class="text-lg pt-20 lg:col-span-8 px-16 lg:px-0">
    <li>
      <x-menu.item title="Edition" :url="route('home')" />
    </li>
    <li>
      <x-menu.item title="Kontakt" :url="route('contact')" :current="request()->routeIs('contact')" />
    </li>
  </ul>
</div>