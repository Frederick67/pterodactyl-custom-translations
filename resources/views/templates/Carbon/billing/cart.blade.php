<body id="body-pd">
    @include('templates.Carbon.inc.header')
    @include('templates.Carbon.inc.navbar', ['active_nav' => 'cart'])
    <div class="grey-bg container-fluid">
      @extends('templates/wrapper', [
      'css' => ['body' => 'bg-neutral-800'],
      ])
    @section('container')
    @inject('BLang', 'Pterodactyl\Models\Billing\BLang')
  
  
    <div class=" py-md pt-5">
  
          <div class="row">

            @include('templates.Carbon.inc.user-widget')
            
              <div class="col-xl-8 order-xl-1">
                <div class="card">
                  <div class="card-header">
                    <div class="row align-items-center">
                      <div class="col-12">
                        <h3 class="mb-0">{!! $BLang::get('checkout') !!}</h3>
                      </div>
                      <div class="col-4 text-right">
                      </div>
                    </div>
                  </div>
                  <div class="card-body">

                  <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{!! $BLang::get('plan_name') !!}</th>
                                <th>{!! $BLang::get('game') !!}</th>
                                <th>{!! $BLang::get('invoice_price') !!}</th>
                                <th class="text-right">{!! $BLang::get('billed') !!}</th>
                                <th class="text-right">{!! $BLang::get('actions') !!}</th>
                            </tr>
                        </thead>
                        <tbody>
                          @php
                            $order_price = 0;
                          @endphp

                          @if(!empty($carts))

                            @foreach($carts as $cart_id => $cart)
                            <tr>
                              <td class="text-center"><img class="img rounded" src="{{ $cart->icon }}" style="width: 25px;margin-right: 25%;margin-left: 25%;"></td>
                              <td>{{ $cart->name }}</td>
                              <td>{{ $cart->getGameName() }}</td>
                              <td>@if(isset($settings['currency_symbol'])){{ $settings['currency_symbol'] }}@endif{{ $cart->price }} @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }}@endif</td>
                              <td class="text-right">@if ($cart->days === 30) {!! $BLang::get('monthly') !!} @elseif ($cart->days  ===  90) {!! $BLang::get('quarterly') !!} @elseif ($cart->days  ===  0) {!! $BLang::get('unlimited') !!} @else {{ $cart->days }} {!! $BLang::get('days') !!} @endif</td>
                              <td class="td-actions text-right">
                                <form action="{{ route('billing.remove.cart') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="plan_id" value="{{ $cart_id }}">
                                  <button type="submit" rel="tooltip" class="btn btn-danger btn-icon btn-sm btn-simple" title="Remove">
                                    <i class='bx bx-x' ></i>
                                  </button>
                                </form>
                              </td>
                            </tr>
                            @php
                            if (isset($cart->price)) {
                              $order_price = $order_price + $cart->price;
                            }
                            @endphp
                            @endforeach
                            
                            @else 
                            <h2>{!! $BLang::get('empty_cart') !!}</h2>
                          @endif
                            
                            
                        </tbody>
                    </table>
                  </div>
                    <a data-toggle="modal" data-target="#orderAll" class="btn btn-primary mt-2" style="width: 100%;">{!! $BLang::get('place_order') !!}</a>
                  </div>
                </div>
              </div>
            </div>
  
    </div>
  
    <div style="width: 100%; margin-block-start: 100px;" class="modal" id="orderAll" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header card-header">
            <h5 class="modal-title">{!! $BLang::get('are_you_sure') !!}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body card-body">
            <strong>{!! $BLang::get('confirm_place_order_info') !!}</strong>
            <p>{!! $BLang::get('total_order') !!} {{ $order_price }} @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }}@endif</p>
          </div>
          <div class="modal-footer card-body">
            <button type="button" class="btn btn-secondary btn-icon btn-sm btn-secondary" data-dismiss="modal">{!! $BLang::get('cancel') !!}</button>
            <a href="{{ route('billing.cart.order.all') }}" rel="tooltip" class="btn btn-success btn-icon btn-sm " title="">{!! $BLang::get('confirm') !!}</a>
          </div>
        </div>
      </div>
    </div>
  
  
    @endsection
    </div>
    @include('templates.Carbon.inc.style')
    @include('templates.Carbon.inc.script')
