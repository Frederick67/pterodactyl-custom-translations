<body id="body-pd">
    @include('templates.Carbon.inc.header')
    @include('templates.Carbon.inc.navbar', ['active_nav' => 'invoices'])
    <div class="grey-bg container-fluid">
      @extends('templates/wrapper', [
      'css' => ['body' => 'bg-neutral-800'],
      ])
    @section('container')
    @inject('BLang', 'Pterodactyl\Models\Billing\BLang')
    
  
    <div class="pt-5">
          <div class="row justify-content-center">

            <div class="col-xl-12 order-xl-1">
              <div class="card">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-12">
                      <h3 class="mb-0">{!! $BLang::get('view_invoices') !!}</h3>
                    </div>
                    <div class="col-4 text-right">
                    </div>
                  </div>
                </div>
                <div class="card-body">

                  <table class="table">
                      <thead>
                          <tr>
                              <th class="text-center">#</th>
                              <th>{!! $BLang::get('date_label') !!}</th>
                              <th>{!! $BLang::get('price_label') !!}</th>
                          </tr>
                      </thead>
                      <tbody>
                        @if(!empty($invoice_logs))
                          @foreach($invoice_logs as $log)
                            <tr>
                              <td class="text-center">#{{ $log->id }}</td>
                              <td>{{ $log->created_at }}</td>
                              <td class="text-danger">{{ $log->data }} @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }}@endif</td>
                            </tr>
                          @endforeach
                        @endif
                          
                      </tbody>
                  </table>
                </div>
              </div>
            </div>
            
          </div>
        </div>
    
      
    
    @endsection
    </div>
    @include('templates.Carbon.inc.style')
    @include('templates.Carbon.inc.script')