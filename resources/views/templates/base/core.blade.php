@inject('BLang', 'Pterodactyl\Models\Billing\BLang')
@if (!empty(config('billing.theme')))
@include('templates.' . config('billing.theme') . '.core')
@else
@include('templates.Default.core')
@endif
{{-- @inject('Cache', 'Illuminate\Support\Facades\Cache')
@if(Cache::has('active_template'))
  @include('templates.' . Cache::get('active_template') . '.core')
@else
  @include('templates.Default.core')
@endif --}}

