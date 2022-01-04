{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}


@extends('layouts.admin')
@include('templates.Carbon.billing.admin.nav', ['activeTab' => 'users'])
@inject('BLang', 'Pterodactyl\Models\Billing\BLang')

@section('title')
Users
@endsection

@section('content-header')
    <h1>Users<small>Manage User Billing Information</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.billing') }}">Billing Module</a></li>
        <li class="active">Users </li>
    </ol>
@endsection

@section('content')
@yield('billing::nav')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Users List</h3>
                <div class="box-tools">

                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th class="">Username</th>
                            <th class="">Balance</th>
                            <th class="text-right">Actions</th>
                        </tr>
                              @foreach($users as $key => $user)
                                @if(isset($user['ptero']->id))
                                <tr>
                                  <td><code>{{ $user['ptero']->id }}</code></td>
                                  <td><a href="{{ route('admin.users.view', ['user' => $user['ptero']->id]) }}">{{ $user['ptero']->name }}</a></td>
                                  <td class="">@if(isset($settings['currency_symbol'])){{ $settings['currency_symbol'] }}@endif{{ $user['billing']->balance }} @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }}@endif</td>
                                  <td class="text-right">
                                    <a data-toggle="modal" data-target="#userBalance{{ $user['ptero']->id }}" href="#" class="btn btn-primary btn-sm">Balance</a>
                                    <a href="{{ route('admin.billing.user.payments', ['id' => $user['ptero']->id]) }}" class="btn btn-primary btn-sm">Payments</a>
                                    <a href="{{ route('admin.billing.user.invoices', ['id' => $user['ptero']->id]) }}" class="btn btn-primary btn-sm">Plans</a>
                                  </td>
                                </tr>

                                <div class="modal fade" id="userBalance{{ $user['ptero']->id }}" tabindex="-1" role="dialog">
                                  <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                          <form id="create_game_id" action="{{ route('admin.billing.users.balance') }}" method="POST">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title">Edit Balance {{ $user['ptero']->name }}</h4>
                                              </div>
                                              <div class="modal-body">
                                                    <input class="form-control" type="text" name="count" value="{{ $user['billing']->balance }}">
                                              </div>
                                              <div class="modal-footer">
                                                  {!! csrf_field() !!}
                                                  <input type="hidden" name="user_id" value="{{ $user['billing']->id }}">
                                                  <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Cancel</button>
                                                  <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                                </div>
                                @endif
                              @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection
