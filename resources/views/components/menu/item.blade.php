@props(['url' => '', 'title' => '', 'current' => '', 'class' => ''])
<a 
  href="{{ $url }}" 
  title="{{ $title }}" 
  class="{{ $class }} hover:text-lime transition-colors {{ $current ? 'text-lime' : '' }}">
  {{ $title }}
</a>