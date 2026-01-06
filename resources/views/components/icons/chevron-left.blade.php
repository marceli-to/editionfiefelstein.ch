@props(['size' => 'md'])

@if ($size === 'sm')
<svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M0 8.5L8 16.5L9.27283 15.2272L2.54559 8.5L9.27283 1.77283L8 0.5L0 8.5Z" fill="currentColor"/>
</svg>
@elseif ($size === 'md')
<svg width="11" height="21" viewBox="0 0 11 21" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M10 0.5L10.4714 0.971405L0.942871 10.5L10.4714 20.0286L10 20.5L0 10.5L10 0.5Z" fill="currentColor"/>
</svg>
@elseif ($size === 'lg')
<svg width="24" height="45" viewBox="0 0 24 45" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M22.5 0L23.5607 1.06067L2.12134 22.5L23.5607 43.9393L22.5 45L0 22.5L22.5 0Z" fill="currentColor"/>
</svg>
@endif
