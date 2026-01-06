@props(['size' => 'sm'])

@if ($size === 'sm')
<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M16 1.56067L14.9393 0.5L8 7.43933L1.06067 0.5L0 1.56067L6.93933 8.5L0 15.4393L1.06067 16.5L8 9.56067L14.9393 16.5L16 15.4393L9.06067 8.5L16 1.56067Z" fill="currentColor"/>
</svg>
@elseif ($size === 'lg')
<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
<path d="M25 1.06067L23.9393 0L12.5 11.4393L1.06067 0L0 1.06067L11.4393 12.5L0 23.9393L1.06067 25L12.5 13.5607L23.9393 25L25 23.9393L13.5607 12.5L25 1.06067Z" fill="currentColor"/>
</svg>
@endif
