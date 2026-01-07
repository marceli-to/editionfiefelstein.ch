@props(['image', 'caption' => null])

<figure>
  <x-media.image :src="$image" :alt="$caption ?? ''" />
  @if($caption)
    <figcaption class="py-16 leading-none">{{ $caption }}</figcaption>
  @endif
</figure>
