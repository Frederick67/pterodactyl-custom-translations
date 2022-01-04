{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}


@extends('layouts.admin')
@include('templates.Carbon.billing.admin.nav', ['activeTab' => 'basic'])
@inject('BLang', 'Pterodactyl\Models\Billing\BLang')

@section('title')
Billing
@endsection

@section('content-header')
<h1>Billing<small>Configure your with ease</small></h1>
<ol class="breadcrumb">
  <li><a href="{{ route('admin.index') }}">Admin</a></li>
  <li class="active">Billing</li>
</ol>
@endsection



@section('content')
@yield('billing::nav')

<div class="row">
  <div class="col-xs-12">
      <form action="{{ route('admin.billing.set.settings') }}" method="POST">
        @csrf
          <div class="box">
              <div class="box-header with-border">
                  <h3 class="box-title">API settings</h3>
              </div>
              <div class="box-body">
                  <div class="row">
                      <div class="form-group col-md-4">
                          
                          <div>
                              <input type="text" required="" class="form-control" name="api_key" value="@if(isset($settings['api_key'])){{ $settings['api_key'] }}@endif"> 
                          </div>
                      </div>
                      <div class="form-group col-md-4">
                        <label class="control-label">CronTab:</label><br>
                          <strong> 0 0 * * * curl {{ route('billing.scheduler') }}</strong>
                      </div>
                  </div>
          <div class="row">
              </div>
           </div>
          </div>

          <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Billing Appearance</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Billing Logo</label>
                        <div>
                            <input type="text" required="" class="form-control" name="logo" value="@if(isset($settings['logo'])){{ $settings['logo'] }}@else /assets/svgs/pterodactyl.svg @endif">
                            <p class="text-muted small">This is the logo displayed through out the site.</p>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                      <label class="control-label">Primary Color</label>
                      <div>
                          <input type="text" required="" class="form-control" name="primary_color" value="@if(isset($settings['primary_color'])){{ $settings['primary_color'] }}@else #4723d9 @endif">
                          <p class="text-muted small">This is the primary color of the panel.</p>
                      </div>
                  </div>

                  <div class="form-group col-md-4">
                    <label class="control-label">Login Page Theme</label>
                    <div>
                      <select class="form-control" name="login_theme" required>
                        <option value="@if(isset($settings['login_theme'])){{ $settings['login_theme'] }}@endif" selected="">@isset($settings['login_theme'])@if( $settings['login_theme'] == "dark") Dark Mode Enabled @else Light Mode Enabled  @endif
                          @endisset</option>
                        <option value="light">Light Mode</option>
                        <option value="dark">Dark Mode</option>
                    </select>
                    </div>
                  </div>

                  <div class="form-group col-md-6">
                    <label class="control-label">Plans Background Image URL</label>
                    <div>
                        <input type="text" required="" class="form-control" name="plans_img_url" value="@if(isset($settings['plans_img_url'])){{ $settings['plans_img_url'] }}@else /billing-src/img/plans.png @endif">
                    </div>
                </div>

                <div class="form-group col-md-6">
                  <label class="control-label">Profile Background Image URL</label>
                  <div>
                      <input type="text" required="" class="form-control" name="profile_img_url" value="@if(isset($settings['profile_img_url'])){{ $settings['profile_img_url'] }}@else /billing-src/img/profile-background.jpg @endif">
                  </div>
              </div>

                    </div>
                  </div>
                </div>
            </div>
        </div>

        <div class="box">
          <div class="box-header with-border">
              <h3 class="box-title">Global Payments Configuration</h3>
          </div>
          <div class="box-body">
              <div class="row">
                <div class="form-group col-md-5">
                  <label class="control-label">Currency</label>
                  <div>
                    <select name="paypal_currency" class="form-control">
                      @if(isset($settings['paypal_currency'])) 
                        <option selected value="{{ $settings['paypal_currency'] }}">{{ $settings['paypal_currency'] }} ({{ $currency_list[$settings['paypal_currency']] }})</option> 
                      @endif
                      @foreach($currency_list as $key => $currency)
                        <option value="{{ $key }}">{{ $key }} ({{ $currency }})</option>
                      @endforeach
                      
                    </select>
                    <p class="text-muted small">Currency</p>
                  </div>
              </div>
              <div class="form-group col-md-2">
                <label class="control-label">Currency Symbol</label>
                <div>
                    <input type="text" required="" class="form-control" name="currency_symbol" value="@if(isset($settings['currency_symbol'])){{ $settings['currency_symbol'] }} @else $ @endif">
                    <p class="text-muted small">Currency Symbol: <code>$, €, £</code> etc...</p>
                </div>
            </div>
              </div>
          </div>
        </div>

          <div class="box">
              <div class="box-header with-border">
                  <h3 class="box-title">PayPal Configuration</h3>
              </div>
              <div class="box-body">
                  <div class="row">

                    <div class="form-group col-md-2">
                      <label class="control-label">PayPal Payment Gateway</label>
                      <div>
                        <select class="form-control" name="paypal_gateway" required>
                          <option value="@if(isset($settings['paypal_gateway'])){{ $settings['paypal_gateway'] }}@endif" selected="">@isset($settings['paypal_gateway'])@if( $settings['paypal_gateway'] == "true") PayPal Enabled @else PayPal Disabled  @endif
                            @endisset</option>
                          <option value="true">Enable</option>
                          <option value="false">Disable</option>
                      </select>
                      </div>
                    </div>
                    
                      <div class="form-group col-md-5">
                          <label class="control-label">PayPal Merchant Email</label>
                          <div>
                              <input type="text" required="" class="form-control" name="paypal_email" value="@if(isset($settings['paypal_email'])){{ $settings['paypal_email'] }}@endif">
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          
          <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Stripe Configuration</h3>
            </div>
            <div class="box-body">
                <div class="row">

                    <div class="form-group col-md-2">
                        <label class="control-label">Stripe Payment Gateway</label>
                        <div>
                          <select class="form-control" name="stripe_gateway" required>
                            <option value="@if(isset($settings['stripe_gateway'])){{ $settings['stripe_gateway'] }}@endif" selected="">@isset($settings['stripe_gateway'])@if( $settings['stripe_gateway'] == "true") Stripe Enabled @else Stripe Disabled  @endif
                              @endisset</option>
                            <option value="true">Enable</option>
                            <option value="false">Disable</option>
                        </select>
                        </div>
                      </div>

                    <div class="form-group col-md-5">
                        <label class="control-label">Publishable key</label>
                        <div>
                            <input type="text" class="form-control" name="publishable_key" value="@if(isset($settings['publishable_key'])){{ $settings['publishable_key'] }}@endif">
                        </div>
                    </div>
                    <div class="form-group col-md-5">
                      <label class="control-label">Secret key</label>
                      <div>
                          <input type="password" class="form-control" name="secret_key" value="@if(isset($settings['secret_key'])){{ $settings['secret_key'] }}@endif">
                      </div>
                  </div>
                    
                </div>
            </div>
        </div>
          <div class="box box-primary">
              <div class="box-footer">
                  <button type="submit" name="_method"  class="btn btn-sm btn-primary pull-right">Save</button>
              </div>
          </div>
      </form>
  </div>
</div>



@endsection

<style>

.game-img-card {
  display: flex;
  justify-content: center;
}

.game-img {
  width: 75px;
}

</style>