<body id="body-pd">
  @include('templates.Carbon.inc.header')
  @include('templates.Carbon.inc.navbar', ['active_nav' => 'billing'])
  <div class="grey-bg container-fluid">
    @extends('templates/wrapper', [
    'css' => ['body' => 'bg-neutral-800'],
    ])
  @section('container')
  @inject('BLang', 'Pterodactyl\Models\Billing\BLang')


  <section class="section team-2">


    <div class="container pt-5">
      <div class="row">

      <div class="col-md-8 mx-auto text-center mb-5">
        <h3 class="display-3">{{ $game->label }} {!! $BLang::get('plans_labal') !!}</h3>
      </div>
      </div>
      <div class="row justify-content-center">
        @foreach($plans as $key => $plan)
      <div class="col-lg-4 col-md-6">
        
        
        <div class="card card-profile" data-image="profile-image">
        <div class="card-header">
          <div class="card-image">
          <a href="javascript:;">
            <img class="img rounded" src="{{ $plan->icon }}" style="width: 50%;margin-right: 25%;margin-left: 25%;">
          </a>
          </div>
        </div>
        
        <div class="card-body pt-0">
          <h4 class="display-4 mb-0 plans-header">{{ $plan->name }}</h4>
          <p class="lead">
            @if(isset($settings['currency_symbol'])){{ $settings['currency_symbol'] }} @endif{{ $plan->price }} @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }} @endif / @if ($plan->days === 30) {!! $BLang::get('monthly') !!} @elseif ($plan->days  ===  90) {!! $BLang::get('quarterly') !!} @elseif ($plan->days  ===  0) {!! $BLang::get('unlimited') !!} @else {{ $plan->days }} {!! $BLang::get('days') !!} 
            @endif
          </p>
          <div class="table-responsive">
          <ul class="list-unstyled" style="margin-bottom: 0;">
            <li class="py-1">
            <div class="d-flex align-items-center">
              <div>
              <div class="badge badge-circle badge-info mr-3"><i class='bx bxs-chip' ></i></div>
              </div>
              <div>
              <h4 class="mb-1">{!! $BLang::get('cpu') !!} {{ $plan->cpu_model }}</h4>
              </div>
            </div>
            </li>
            <li class="py-1">
            <div class="d-flex align-items-center">
              <div>
              <div class="badge badge-circle badge-success mr-3"><i class='bx bxs-microchip' ></i> </div>
              </div>
              <div>
              <h4 class="mb-1">{!! $BLang::get('ram') !!} @if ($plan->memory === 0) {!! $BLang::get('unlimited') !!} @else {{ $plan->memory }} MB @endif</h4>
              </div>
            </div>
            </li>
            <li class="py-1">
            <div class="d-flex align-items-center">
              <div>
              <div class="badge badge-circle mr-3" style="background: #FFFF; color: #563cc3;"><i class='bx bxs-hdd'></i> </div>
              </div>
              <div>
              <h4 class="mb-1">{!! $BLang::get('storage') !!} @if ($plan->disk_space === 0) {!! $BLang::get('unlimited') !!} @else {!! $plan->disk_space !!} MB @endif</h4>
              </div>
            </div>
            </li>
          </ul>
          <ul class="list-unstyled">
            <li class="py-1">
            <div class="d-flex align-items-center">
              <div>
              <div class="badge badge-circle badge-info mr-3"><i class='bx bxs-copy'></i></div>
              </div>
              <div>
              <h4 class="mb-1">{{ $plan->backup_limit }} {!! $BLang::get('backup') !!} </h4>
              </div>
            </div>
            </li>
            <li class="py-1">
            <div class="d-flex align-items-center">
              <div>
              <div class="badge badge-circle badge-success mr-3"><i class='bx bxs-data' ></i></div>
              </div>
              <div>
              <h4 class="mb-1">{{ $plan->database_limit }} {!! $BLang::get('database') !!} </h4>
              </div>
            </div>
            </li>
            <li class="py-1">
            <div class="d-flex align-items-center">
              <div>
              <div class="badge badge-circle mr-3" style="background: #FFFF; color: #563cc3;"><i class='bx bx-wifi'></i></div>
              </div>
              <div>
              <h4 class="mb-1">{{ $plan->allocation_limit }} {!! $BLang::get('exstra_ports') !!}</h4>
              </div>
            </div>
            </li>
          </ul>
          </div>
            <div>
              <h2 style="padding-top: 15px;">{!! $BLang::get('description') !!}</h2>
              <a>{!! $plan->description !!}</a>
            <form action="{{ route('billing.add.cart') }}" method="POST">
              @csrf
              <input type="hidden" name="plan_id" value="{{ $plan->id }}">
              <button type="submit" style="width: 100%; margin-top: 15px;" class="btn btn-primary btn-round"><i class='bx bx-basket' ></i>{!! $BLang::get('add_to_cart') !!}</button>
            </form>  
            </div>
        </div>

        </div>

       


      </div>
      @endforeach


    </section>




  @endsection
  </div>
  @include('templates.Carbon.inc.style')
  @include('templates.Carbon.inc.script')