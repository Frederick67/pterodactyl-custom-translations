{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}


@extends('layouts.admin')
@include('templates.Carbon.billing.admin.nav', ['activeTab' => 'update'])
@inject('BLang', 'Pterodactyl\Models\Billing\BLang')

@section('title')
Plans
@endsection

@section('content-header')
<h1>Billing Module<small>New updates will be visible here.</small></h1>
<ol class="breadcrumb">
	<li><a href="{{ route('admin.index') }}">Admin</a></li>
    <li><a href="{{ route('admin.billing') }}">Billing Module</a></li>
	<li class="active">Update </li>
</ol>
@endsection



@section('content')
@yield('billing::nav')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">currently running version {{ config('billing.version') }} of the {{ config('billing.name') }} Module.</h3>
			</div>
			<h4 style="padding: 15px;"><strong>
			Module Authors: {{ config('billing.author') }}	<br>
			System Language: {{ config('billing.system_lang') }}	<br>
			Module Version: {{ config('billing.version') }}	<br>
			Pterodactyl Version {{ config('app.version') }}	<br>
			</h4></strong>
            <iframe src="https://updates.mubeen.eu/{{ config('billing.name') }}/v={{ config('billing.version') }}" style="height:400px;width:100%;border:2px solid transparent;margin-top:20px; padding: 10px;" style="display: none;"> </iframe>
		</div>
	</div>
</div>
@endsection