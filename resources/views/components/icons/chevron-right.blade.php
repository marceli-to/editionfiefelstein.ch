@props(['size' => 'md'])

@if ($size === 'xs')
<svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M5.84851 5.5L0.848572 0.5L0 1.34851L4.15149 5.5L0 9.65149L0.848572 10.5L5.84851 5.5Z" fill="currentColor"/>
</svg>
@elseif ($size === 'md')
<svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M0.471436 0.5L0 0.971405L9.52856 10.5L0 20.0286L0.471436 20.5L10.4714 10.5L0.471436 0.5Z" fill="currentColor"/>
</svg>
@elseif ($size === 'lg')
<svg width="24" height="45" viewBox="0 0 24 45" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M1.06067 0L0 1.06067L21.4393 22.5L0 43.9393L1.06067 45L23.5607 22.5L1.06067 0Z" fill="currentColor"/>
</svg>
@endif
