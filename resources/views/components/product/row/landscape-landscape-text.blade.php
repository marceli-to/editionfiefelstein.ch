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
  <div class="bg-lime text-sm 2xl:text-md [&_p]:mb-16 last:[&_p]:mb-0 p-16 md:col-span-4 md:overflow-y-auto tracking-normal">
    {!! $row['text'] ?? '' !!}
  </div>
</div>
