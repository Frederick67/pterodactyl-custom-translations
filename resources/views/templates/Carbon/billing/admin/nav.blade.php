@include('templates.Carbon.billing.admin.notice')
@inject('BLang', 'Pterodactyl\Models\Billing\BLang')
@section('billing::nav')
    @yield('billing::notice')
    <style>.fa, .fas{padding-left:3px;}</style>
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom nav-tabs-floating">
                <ul class="nav nav-tabs">
                    <li @if($activeTab === 'basic')class="active"@endif><a href="{{ route('admin.billing') }}"><i class="fas fa-cog"></i> General</a></li>
                    <li @if($activeTab === 'games')class="active"@endif><a href="{{ route('admin.billing.games') }}"><i class="fas fa-cube"></i> Games</a></li>
                    <li @if($activeTab === 'plans')class="active"@endif><a href="{{ route('admin.billing.plans') }}"><i class="fas fa-cubes"></i> Plans</a></li>
                    <li @if($activeTab === 'users')class="active"@endif><a href="{{ route('admin.billing.users') }}"><i class="fas fa-users"></i> Users</a></li>
                    <li @if($activeTab === 'pages')class="active"@endif><a href="{{ route('admin.billing.pages') }}"><i class="fas fa-list"></i> Pages</a></li>
                    <li @if($activeTab === 'alerts')class="active"@endif><a href="{{ route('admin.billing.alerts') }}"><i class="fas fa-exclamation-triangle"></i> Alerts</a></li>
                    <li @if($activeTab === 'meta')class="active"@endif><a href="{{ route('admin.billing.meta') }}"><i class="fas fa-link"></i> Meta</a></li>
                    <li @if($activeTab === 'update')class="active"@endif><a href="{{ route('admin.update') }}"><i class="fas fa-cloud-download-alt"></i> Update</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
