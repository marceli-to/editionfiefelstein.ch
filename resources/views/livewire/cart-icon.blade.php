<a
  href="javascript:;"
  class="inline-flex items-center gap-x-6"
  x-on:click="$dispatch('toggle-cart')">
  @if ($cartItemCount > 0)
    <x-icons.quantity :quantity="$cartItemCount" class="w-24 h-auto" />
  @endif
  <x-icons.cart />
</a>
