@extends('app')
@section('content')
<div class="flex flex-col gap-y-32 md:grid md:grid-cols-8 gap-x-16 lg:mt-84 about-page">
  <div class="md:col-span-4 lg:col-span-6 xl:col-start-2">
    @if ($data->quote)
      <blockquote class="text-lg font-europa-light font-light">
        {{ $data->quote }}
      </blockquote>
      @if ($data->quote_author)
        <span class="block mt-16">
          <strong>{{ $data->quote_author }}</strong>
        </span>
      @endif
    @endif

    @if ($data->text)
      <div class="text-lg font-europa-light font-light mt-32 lg:mt-64 [&_p]:mt-24 leading-[1.3]">
        {!! $data->text !!}
      </div>
    @endif


  </div>

</div>
@endsection
