<body id="body-pd">
  @include('templates.Carbon.inc.header')
  @include('templates.Carbon.inc.navbar', ['active_nav' => $page->url])
  <div class="grey-bg container-fluid">
    @extends('templates/wrapper', [
    'css' => ['body' => 'bg-neutral-800'],
    ])

  @section('container')
  @inject('BLang', 'Pterodactyl\Models\Billing\BLang')
  
  <div class="mt-3 p-3">
  {!! html_entity_decode($page->data) !!}
  </div>
  
    
  
  @endsection
  </div>
  @include('templates.Carbon.inc.style')
  @include('templates.Carbon.inc.script')