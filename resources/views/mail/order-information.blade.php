<x-mail::message>
  <div class="main text-base">
    <h1>Folgende Bestellung ist eingegangen:</h1>
    <div class="table">
      <table cellpadding="0" cellspacing="0">
        @foreach($data->orderProducts as $product)
          <tr>
            <td colspan="2">{{ $product->title }}</td>
            <td class="quantity">{{ $product->quantity }}</td>
          </tr>
          <tr>
            <td>ISBN {{ $product->isbn }}</td>
            <td class="currency">CHF</td>
            <td class="amount text-right">{!! number_format($product->price, 2, '.', '&thinsp;') !!}</td>
          </tr>
        @endforeach
        <tr>
          <td>Verpackung und Versand</td>
          <td class="currency">CHF</td>
          <td class="amount text-right">{!! number_format($data->shipping_cost, 2, '.', '&thinsp;') !!}</td>
        </tr>
        <tr>
          <td><strong>Total</strong></td>
          <td class="currency"><strong>CHF</strong></td>
          <td class="amount text-right"><strong>{!! number_format($data->total, 2, '.', '&thinsp;') !!}</strong></td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">Lieferadresse</td>
        </tr>
        @if ($data->use_invoice_address)
          @if ($data->salutation)
            <tr>
              <td colspan="3">{{ $data->salutation }}</td>
            </tr>
          @endif
          <tr>
            <td colspan="3">{{ $data->invoice_name }}</td>
          </tr>
          <tr>
            <td colspan="3">{{ $data->invoice_address }}</td>
          </tr>
          <tr>
            <td colspan="3">{{ $data->invoice_location }}</td>
          </tr>
          <tr>
            <td colspan="3">{{ $data->country }}</td>
          </tr>
        @else
          <tr>
            <td colspan="3">{{ $data->shipping_full_name }}</td>
          </tr>
          @if ($data->shipping_company)
            <tr>
              <td colspan="3">{{ $data->shipping_company }}</td>
            </tr>
          @endif
          <tr>
            <td colspan="3">{{ $data->shipping_address }}</td>
          </tr>
          <tr>
            <td colspan="3">{{ $data->shipping_location }}</td>
          </tr>
          <tr>
            <td colspan="3">{{ $data->shipping_country }}</td>
          </tr>
        @endif
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">Zahlung</td>
        </tr>
        <tr>
          <td>{{ $data->payment_info }}</td>
          <td class="currency">CHF</td>
          <td class="amount text-right">{{ $data->total }}</td>
        </tr>
      </table>
    </div>
    <div class="pt-xl">
      <a href="https://www.instagram.com/fiefelstein/" target="_blank" title="editionfiefelstein.ch auf Instagram">
        <img src="{{ config('app.url') }}/img/instagram.png" alt="editionfiefelstein.ch auf Instagram" height="20" width="20" style="display:block; height:auto; width: 20px;">
      </a>
    </div>
  </div>
</x-mail::message>
