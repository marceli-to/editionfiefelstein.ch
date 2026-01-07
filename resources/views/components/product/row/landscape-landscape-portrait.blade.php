@props(['row'])

<div class="flex flex-col gap-16 mb-16 md:grid md:grid-cols-8">
  <div class="flex flex-col gap-16 md:col-span-4">
    <div class="aspect-[4/3]">
      <x-media.image :src="$row['landscape_1']" alt="" class="w-full h-full object-cover" />
    </div>
    <div class="aspect-[4/3]">
      <x-media.image :src="$row['landscape_2']" alt="" class="w-full h-full object-cover" />
    </div>
  </div>
  <div class="md:col-span-4">
    <x-media.image :src="$row['portrait']" alt="" class="w-full h-full object-cover" />
  </div>
</div>
