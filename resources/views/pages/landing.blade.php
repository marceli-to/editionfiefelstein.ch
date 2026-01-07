@extends('app')
@section('content')

<x-product.hero :image="$product->image" :caption="$product->image_caption" />

@if($product->rows)
  @foreach($product->rows as $row)
    <x-product.row :row="$row" />
  @endforeach
@endif

<x-product.content :product="$product" />

@endsection