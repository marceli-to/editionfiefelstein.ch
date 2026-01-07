@props(['row'])

@switch($row['layout'])
  @case('2_landscape_1_portrait')
    <x-product.row.landscape-landscape-portrait :row="$row" />
    @break
  @case('1_portrait_2_landscape')
    <x-product.row.portrait-landscape-landscape :row="$row" />
    @break
  @case('text_2_landscape')
    <x-product.row.text-landscape-landscape :row="$row" />
    @break
  @case('2_landscape_text')
    <x-product.row.landscape-landscape-text :row="$row" />
    @break
@endswitch
