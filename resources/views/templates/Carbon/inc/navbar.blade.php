
@inject('BillingSettings', 'Pterodactyl\Models\Billing\BillingSettings')
@inject('BLang', 'Pterodactyl\Models\Billing\BLang')

<div class="l-navbar" id="nav-bar" style="z-index: 40;">
  <nav class="nav">
      <div style="height: 100%"> 
            <a class="logo-expand site-logo" href="/" >
            <img onclick="window.location.href='/'" src="@if(isset($settings['logo'])){{ $settings['logo'] }}@else /assets/svgs/pterodactyl.svg @endif" style="width: 42px; padding: 4px;">{{ config('app.name', 'Pterodactyl') }}</a>

          <div class="nav_list" style="overflow: auto; height: 90%;"> 
            <div class="dropdown-divider"></div>
            <a href="{{ route('billing.link') }}" class="nav_link @if($active_nav == 'billing') sb-active @endif" id="sbBilling"><i class='bx bxs-package nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('billing_page') !!}</span> </a>
            <a href="{{ route('billing.balance') }}" class="nav_link @if($active_nav == 'balance') sb-active @endif" id="sbBalance"><i class='bx bx-wallet nav_icon sidebar-card'></i> <span class="nav_name">{!! $BLang::get('balance_page') !!}</span> </a>
            <a href="{{ route('billing.cart') }}" class="nav_link @if($active_nav == 'cart') sb-active @endif" id="sbCart"><i class='bx bx-basket nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('cart_page') !!}</span> </a>
            <a href="{{ route('billing.invoices') }}" class="nav_link @if($active_nav == 'invoices') sb-active @endif" id="sbInvoices"><i class='bx bx-receipt nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('plan_page') !!}</span> </a>
            <a href="/" class="nav_link"> <i class='bx bx-server nav_icon sidebar-card'></i> <span class="nav_name">{!! $BLang::get('servers_page') !!}</span> </a> 
            <div class="dropdown-divider"></div>
            <a href="/tickets" class="nav_link"> <i class='bx bx-conversation nav_icon sidebar-card' ></i> <span class="nav_name">Tickets</span> </a>
            <a href="/tickets/new" class="nav_link"> <i class='bx bx-support nav_icon sidebar-card' ></i> <span class="nav_name">Create Ticket</span> </a>
            <a href="/announcements" class="nav_link"> <i class='bx bxs-news nav_icon sidebar-card' ></i> <span class="nav_name">Announcements</span> </a>
			<a href="https://phpmyadmin.lunarnodes.eu/" target="_blank" class="nav_link"> <i class='bx bxl-php nav_icon sidebar-card' ></i> <span class="nav_name">PhpMyAdmin</span> </a>
			<a href="https://discord.gg/tCr6kzYgbb" target="_blank" class="nav_link"> <i class='bx bxl-discord nav_icon sidebar-card' ></i> <span class="nav_name">Discord</span> </a>
              @if(count($BillingSettings::getCustomPages()))
                <div class="dropdown-divider"></div>
                
                @foreach($BillingSettings::getCustomPages() as $key => $page)
                  <a href="{{ route('billing.custom.page', ['page' => $page->url]) }}" class="nav_link @if($active_nav == $page->url) sb-active @endif" id="sbBillingPage{{ $page->id }}"><i class="{{ $page->icon }} nav_icon sidebar-card "></i> <span class="nav_name">{{ ucfirst($page->url) }}</span> </a>
                @endforeach
              @endif

              <div class="dropdown-divider"></div>
              <a onClick="openConsole()" class="nav_link" id="sbConsole" style="display: none;"> <i class='bx bx-terminal nav_icon sidebar-card'></i> <span class="nav_name">{!! $BLang::get('console_page') !!}</span> </a>
              <a onClick="openFiles()" class="nav_link" id="sbFiles" style="display: none;"> <i class='bx bx-folder nav_icon sidebar-card'></i> <span class="nav_name">{!! $BLang::get('files_page') !!}</span> </a>
              <a onClick="openDatabases()" class="nav_link" id="sbDatabases" style="display: none;"> <i class='bx bx-data nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('databases_page') !!}</span> </a> 
              <a onClick="openSchedules()" class="nav_link" id="sbSchedules" style="display: none;"> <i class='bx bx-time-five nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('shedulers_page') !!}</span> </a>  
              <a onClick="openUsers()" class="nav_link" id="sbUsers" style="display: none;"> <i class='bx bx-user nav_icon sidebar-card nav_icon sidebar-card'></i> <span class="nav_name">{!! $BLang::get('users_page') !!}</span> </a> 
              <a onClick="openBackups()" class="nav_link" id="sbBackups" style="display: none;"> <i class='bx bx-cloud-download nav_icon sidebar-card'></i> <span class="nav_name">{!! $BLang::get('backups_page') !!}</span> </a> 
              <a onClick="openSubdomain()" class="nav_link" id="subdomain" style="display: none;"> <i class='bx bx-world nav_icon sidebar-card' ></i> <span class="nav_name">Subdomain</span> </a>
              <a onClick="openVersion()" class="nav_link" id="version" style="display: none;"> <i class='bx bx-git-repo-forked nav_icon sidebar-card' ></i> <span class="nav_name">Version</span> </a>
              <a onClick="openNetwork()" class="nav_link" id="sbNetwork"  style="display: none;"> <i class='bx bx-network-chart nav_icon sidebar-card'></i> <span class="nav_name">{!! $BLang::get('network_page') !!}</span> </a> 
              <a onClick="openStartup()" class="nav_link" id="sbStartup" style="display: none;"> <i class='bx bx-slider nav_icon sidebar-card'></i>  <span class="nav_name">{!! $BLang::get('startup_page') !!}</span> </a> 
              <a onClick="openSettings()" class="nav_link" id="sbSettings" style="display: none;"> <i class='bx bx-briefcase-alt-2 nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('settings_page') !!}</span> </a> 
              @if(Auth::user()->root_admin)
              <a onClick="openManage()" class="nav_link" id="sbManage" style="display: none;"> <i class='bx bx-wrench nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('manage_page') !!}</span> </a>
              @endif
              

              <a href="/account" class="nav_link"> <i class='bx bx-user nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('account_page') !!}</span> </a>
              @if(Auth::user()->root_admin)
               <a href="{{ route('admin.billing') }}" class="nav_link"> <i class='bx bx-key nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('admin_page') !!}</span> </a>
              @endif
              <a href="{{ route('auth.logout') }}" class="nav_link"> <i class='bx bx-power-off nav_icon sidebar-card' ></i> <span class="nav_name">{!! $BLang::get('out_page') !!}</span> </a>
          </div>
      </div>
  </nav>
</div>

    <!--Container Main start-->

  <button type="button" style="display: none;background-color: var(--first-color);width: 100%; margin-bottom: 10px; border-color: transparent; text-transform: uppercase;top: 7px;" id="output-server-status" class="btn btn-primary btn-lg btn-block">{!! $BLang::get('connecting') !!}</button>


</section>
</div>

@include('templates.Carbon.inc.alerts')

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: @isset($settings['alerts_status'])@if( $settings['alerts_status'] == "true") 20px; @else 80px; @endif @endisset">
  <span class="alert-inner--icon"><i class='bx bx-x'></i></span>
  <span class="alert-inner--text"><strong>{!! $BLang::get('error') !!}</strong> @foreach ($errors->all() as $error){{ $error }}@endforeach</span>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif


@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: @isset($settings['alerts_status'])@if( $settings['alerts_status'] == "true") 20px; @else 80px; @endif @endisset">
      <span class="alert-inner--icon"><i class='bx bx-check'></i></span>
      <span class="alert-inner--text"><strong>{!! $BLang::get('success') !!}</strong> {{ session()->get('success') }}</span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
@endif
