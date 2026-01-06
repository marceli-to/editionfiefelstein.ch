@props(['size' => 'sm'])

@if ($size === 'sm')
<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <path d="M16 1.56067L14.9393 0.5L8 7.43933L1.06067 0.5L0 1.56067L6.93933 8.5L0 15.4393L1.06067 16.5L8 9.56067L14.9393 16.5L16 15.4393L9.06067 8.5L16 1.56067Z" fill="currentColor"/>
</svg>
@elseif ($size === 'lg')
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <path d="M22.6 0L12 10.6L1.40002 0L0 1.39999L10.6 12L0 22.6L1.40002 24L12 13.4L22.6 24L24 22.6L13.4 12L24 1.39999L22.6 0Z" fill="currentColor"/>
</svg>
@endif
