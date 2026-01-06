<a
  href="javascript:;"
  x-on:click="menu = !menu; cart = false"
  x-show="!cart"
  class="w-32 h-24 flex items-center justify-center relative z-70">
  <span x-show="menu === false">
    <x-icons.burger class="w-full h-full" />
  </span>
  <span x-cloak x-show="menu === true">
    <x-icons.cross size="lg" />
  </span>
</a>