<body id="body-pd">
    @include('templates.Carbon.inc.header')
    @include('templates.Carbon.inc.navbar', ['active_nav' => 'invoices'])
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
                        <h3 class="mb-0">{!! $BLang::get('plan_page') !!}</h3>
                      </div>
                      <div class="col-4 text-right">
                      </div>
                    </div>
                  </div>
                  <div class="card-body" style="display: flex;">

                    <div class="row">

                      @if(!empty($invoices))
                      @foreach($invoices as  $invoice)
                        @if(isset($plans[$invoice->plan_id]))
                          
                        
                      
                          <div class="col-sm">
                            <div class="card" style="width: 18rem; background: transparent;">
                              <img class="card-img-top" src="@if(isset($settings['plans_img_url'])){{ $settings['plans_img_url'] }}@else /billing-src/img/plans.png @endif" alt="Plan Icon">
                              <div class="card-body">
                                <h3 class="card-title" style="display: flex;flex-direction: row;align-items: center;justify-content: space-between;">{{ $plans[$invoice->plan_id]->name }}<span class="badge badge-pill badge-@if($invoice->status == 'Paid')success @elseif($invoice->status == 'Unpaid')danger @endif ">{{ $invoice->status }}</span></h3>                            
                                <p class="card-text">
                                  #{{ $invoice->id }}<br>
                                  {!! $BLang::get('invoice_date') !!} {{ $invoice->invoice_date }}<br>
                                  {!! $BLang::get('due_date') !!} {{ $invoice->due_date }}<br>
                                  {!! $BLang::get('invoice_price') !!} @if(isset($plans[$invoice->plan_id])){{ $settings['currency_symbol'] }} {{ $plans[$invoice->plan_id]->price }} {{ $settings['paypal_currency'] }}@endif <br>


                                </p>
                                <a data-toggle="modal" data-target="#upadtaInvoice{{ $invoice->id }}" rel="tooltip" class="btn btn-success mb-2"  style="width: 100%"><i class='bx bx-sync'></i> {!! $BLang::get('renew_plan') !!}</a>
                                <a href="{{ route('billing.invoice.view', ['id' => $invoice->id]) }}" class="btn btn-info mb-2" style="width: 100%"><i class='bx bxs-receipt'></i>{!! $BLang::get('view_invoices') !!}</a>
                                <a data-toggle="modal" data-target="#deleteInvoice{{ $invoice->id }}" rel="tooltip"class="btn btn-danger mb-2" style="width: 100%"><i class='bx bx-trash'></i>{!! $BLang::get('request_cancellation') !!}</a>


                              </div>
                            </div>
                          </div>

                          <div style="width: 100%; margin-block-start: 100px;" class="modal" id="upadtaInvoice{{ $invoice->id }}" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header card-header">
                                  <h5 class="modal-title">@if(isset($plans[$invoice->plan_id])){{ $plans[$invoice->plan_id]->name }}@endif</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body card-body">
                                  {!! $BLang::get('extend_plan_info') !!}
                                  @if(isset($plans[$invoice->plan_id]))
                                  {!! $BLang::get('extend') !!} {{ $plans[$invoice->plan_id]->days }}{!! $BLang::get('days_for') !!} @if(isset($settings['currency_symbol'])){{ $settings['currency_symbol'] }}@endif{{ $plans[$invoice->plan_id]->price }} @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }}@endif
                                  @else
                                  {!! $BLang::get('deleted') !!}
                                  @endif
                                </div>
                                <div class="modal-footer card-body">
                                  <button type="button" class="btn btn-secondary btn-icon btn-sm btn-secondary" data-dismiss="modal">{!! $BLang::get('close') !!}</button>
                                  <a href="{{ route('billing.invoice.update', ['id' => $invoice->id]) }}" rel="tooltip" class="btn btn-info btn-icon btn-sm " data-original-title="" title=""><i class='bx 
                                    bxl-paypal'></i>{!! $BLang::get('pay') !!}</a>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div style="width: 100%; margin-block-start: 100px;" class="modal" id="deleteInvoice{{ $invoice->id }}" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header card-header">
                                  <h5 class="modal-title">@if(isset($plans[$invoice->plan_id])){{ $plans[$invoice->plan_id]->name }}@endif</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body card-body">
                                  <strong>{!! $BLang::get('remove_plan_info') !!}
                                    <p><h3 class="text-center">{!! $BLang::get('are_you_sure') !!}</h3></p>
                                  </strong>
                                </div>
                                <div class="modal-footer card-body">
                                  <button type="button" class="btn btn-secondary btn-icon btn-sm btn-secondary" data-dismiss="modal">{!! $BLang::get('close') !!}</button>
                                  <a href="{{ route('billing.invoice.delete', ['id' => $invoice->id]) }}" rel="tooltip" class="btn btn-danger btn-icon btn-sm " data-original-title="" title=""><i class='bx bx-x'></i>{!! $BLang::get('remove') !!}</a>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endforeach
                      @endif

                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
  
    </div>
  
  
  
  
    @endsection
    </div>
    @include('templates.Carbon.inc.style')
    @include('templates.Carbon.inc.script')