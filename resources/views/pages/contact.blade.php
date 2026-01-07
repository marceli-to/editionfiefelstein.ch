@extends('app')
@section('content')
<div class="flex flex-col gap-y-32 md:grid md:grid-cols-8 gap-x-16 lg:mt-90 relative contact-page">
  <div class="md:col-span-2 xl:col-start-2 contact-page__imprint">
    {!! $data->imprint ?? '' !!}
  </div>
  <div class="md:col-span-6 xl:col-span-4 contact-page__toc">
    @if ($data->copyright)
      <h2 class="!mb-0">Copyright, {{ date('Y') }}</h2>
      {!! $data->copyright !!}
    @endif

    @if ($data->toc_title)
    <h2>{!! nl2br($data->toc_title) !!}</h2>
    @endif

    @if ($data->toc_items)
      @foreach($data->toc_items as $item)
        <h3 class="flex space-x-16">
          @if ($item['number'])
          <span>{{ $item['number'] }}</span>
          @endif
          <span>{{ $item['title'] }}</span>
        </h3>
        <div>
          {!! $item['text'] !!}
        </div>
      @endforeach
    @endif
    
    @if ($data->privacy)
      <div class="contact-page__toc__privacy">
        {!! $data->privacy !!}
      </div>
    @endif
  </div>
</div>
@endsection