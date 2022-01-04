{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}


@extends('layouts.admin')
@include('templates.Carbon.billing.admin.nav', ['activeTab' => 'plans'])
@inject('BLang', 'Pterodactyl\Models\Billing\BLang')

@section('title')
Plans
@endsection

@section('content-header')
    <h1>Plans<small>Create New Plans</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.billing') }}">Billing Module</a></li>
        <li class="active">Plans</li>
    </ol>
@endsection

@section('content')
@yield('billing::nav')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Plans List</h3>
                <div class="box-tools">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newPlansModal">Create New</button>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Plan Name</th>
                            <th>Game</th>
                            <th class="text-center">Duration</th>
                            <th class="text-center">Price</th>
                            <th class="text-right">Actions</th>
                        </tr>
                          @foreach($plans as $key => $plan)
                            <tr>
                              <td><code>{{ $plan->id }}</code></td>
                              <td>{{ $plan->name }}</td>
                              <td>{{ $games[$plan->game_id]->label }}</td>
                              <td class="text-center">@if ($plan->days === 30) Monthly @elseif ($plan->days  ===  90) Quarterly @elseif ($plan->days  ===  999) Unlimited @else {{ $plan->days }} days @endif</td>
                              <td class="text-center">@if(isset($settings['currency_symbol'])){{ $settings['currency_symbol'] }} @endif{{ $plan->price }} @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }} @endif</td>
                              <td class="text-right">
                                <a data-toggle="modal" data-target="#editPlansModal{{ $plan->id }}" class="btn btn-primary btn-sm">Edit</a>
                                <a onclick="deleteModal('{{ $plan->id }}')" data-toggle="modal" data-target="#deletePlanModal" class="btn btn-danger btn-sm">Delete</a>
                              </td>
                            </tr>



                            <div class="modal fade" id="editPlansModal{{ $plan->id }}" tabindex="-1" role="dialog">
                              <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                      <form action="{{ route('admin.billing.edit.plan') }}" method="POST">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <h4 class="modal-title">Edit Plan</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row">
                            
                                                  <div class="col-md-6">
                                                      <label for="plan_name" class="form-label">Plan Name</label>
                                                      <input type="text" name="name" id="edit_plan_name" class="form-control" value="{{ $plan->name }}" />
                                                      <p class="text-muted small">Name of the plan</p>
                                                  </div>
                            
                                                  <div class="col-md-6">
                                                      <label for="plan_price" class="form-label">Price in @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }}@endif</label>
                                                      <input type="number" step="0.01" name="price" id="edit_plan_price" class="form-control" value="{{ $plan->price }}"/>
                                                      <p class="text-muted small">Set the price for your plan @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }} @endif</p>
                                                  </div>
                            
                                                  <div class="col-md-6">
                                                      <label for="plan_icon" class="form-label">Icon URL</label>
                                                      <input type="text" name="icon" id="edit_plan_icon" class="form-control" value="{{ $plan->icon }}"/>
                                                      <p class="text-muted small">Icon of plan, image must end with an extension</p>
                                                  </div>
                            
                                                  <div class="col-md-6">
                                                      <label for="plan_cpu_model" class="form-label">CPU</label>
                                                      <input type="text" name="cpu_model" id="edit_plan_cpu_model" class="form-control" value="{{ $plan->cpu_model }}"/>
                                                      <p class="text-muted small">Set the CPU for the server</p>
                                                  </div>

                                                  <div class="col-md-6">
                                                    <label for="plan_node" class="form-label">Node</label>
                                                    <select name="node" id="plan_node" class="form-control">
                                                      @if(isset($nodes))
                                                        @foreach($nodes['data'] as $key => $node)
                                                        <option value="{{ $node['attributes']['id'] }}" @if($plan->node == $node['attributes']['id']) selected @endif>{{ $node['attributes']['name'] }}</option>
                                                        @endforeach
                                                      @endif
                                                   
                                                    </select>
                                                    <p class="text-muted small">Set the Node id for your plan</p>
                                                  </div>
                                                  <div class="col-md-6">
                                                    <label for="plan_limit" class="form-label">Purchase Limit (Per user) </label>
                                                    <input class="form-control" type="number" name="limit" id="plan_limit" value="{{ $plan->limit }}">
                                                    <p class="text-muted small">Set to <code>0</code> for unlimited.</p>
                                                  </div>

                                                  <div class="col-md-12">
                                                      <label for="plan_description" class="form-label">Description</label>
                                                      <textarea name="description" id="edit_plan_description" class="form-control" rows="4">{{ $plan->description }}</textarea>
                                                      <p class="text-muted small">A longer description of your plan.</p>
                                                  </div>
                                                  <div class="col-md-12">
                                                    <label for="plan_variables" class="form-label">Variables</label>
                                                    <textarea name="variables" id="plan_variables" class="form-control" rows="4" placeholder="BUNGEE_VERSION = latest&#10;SERVER_JARFILE = server.jar">@foreach( json_decode($plan->variable) as $key => $val)@foreach($val as $key => $value){{ $key }} = {{ $value }}@endforeach&#10;@endforeach
                                                    </textarea>
                                                    <p class="text-muted small">Variable of your egg.</p>
                                                  </div>                         
                            
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                              {!! csrf_field() !!}
                                              <input type="hidden" id="edit_plan_id" name="plan_id" value="{{ $plan->id }}">
                                              <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Cancel</button>
                                              <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                            </div>
                          @endforeach
                            
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newPlansModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.billing.create.plan') }}" method="POST" id="create_plan_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create Plan</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <label for="plan_name" class="form-label">Plan Name</label>
                            <input type="text" name="name" id="plan_name" class="form-control" />
                            <p class="text-muted small">Name of the plan</p>
                        </div>

                        <div class="col-md-6">
                            <label for="plan_price" class="form-label">Price in @if(isset($settings['paypal_currency'])){{ $settings['paypal_currency'] }}@endif</label>
                            <input type="number" step="0.01" name="price" id="plan_price" class="form-control" />
                            <p class="text-muted small">Set the price for your plan</p>
                        </div>

                        <div class="col-md-6">
                            <label for="plan_icon" class="form-label">Icon URL</label>
                            <input type="text" name="icon" id="plan_icon" class="form-control" />
                            <p class="text-muted small">Icon of plan, image must end with an extension</p>
                        </div>

                        <div class="col-md-6">
                            <label for="plan_cpu_model" class="form-label">CPU</label>
                            <input type="text" name="cpu_model" id="plan_cpu_model" class="form-control" value="AMD EPYC 7282" />
                            <p class="text-muted small">Set the CPU for the server</p>
                        </div>

                        <div class="col-md-4">
                            <label for="plan_game_id" class="form-label">Game</label>
                            <select name="game_id" id="plan_game_id" class="form-control">
                              @foreach($games as $key => $game)
                                <option value="{{ $game->id }}">{{ $game->label }}</option>
                              @endforeach
                            </select>              
                            <p class="text-muted small">Set the Game for your plan</p>
                        </div>
                        <div class="col-md-4">
                          <label for="plan_egg" class="form-label">Egg</label>
                          <select name="egg" id="plan_egg" class="form-control">
                            @if(isset($eggs))
                              @foreach($eggs as $key => $egg)
                              <option value="{{ $egg['id'] }}">{{ $egg['name'] }}</option>
                              @endforeach    
                            @endif
                         
                          </select>
                          <p class="text-muted small">Set the Egg id for your plan</p>
                      </div>
                        <div class="col-md-4">
                          <label for="plan_days" class="form-label">Days</label>
                          <input type="number" name="days" id="plan_days" class="form-control" />
                          <p class="text-muted small">Duration of plan: <code>999 = Unlimited, 30 = month, 90 = 3 months</code></p>
                        </div>

                        <div class="col-md-6">
                          <label for="plan_node" class="form-label">Node</label>
                          <select name="node" id="plan_node" class="form-control">
                            @if(isset($nodes))
                              @foreach($nodes['data'] as $key => $node)
                              <option value="{{ $node['attributes']['id'] }}">{{ $node['attributes']['name'] }}</option>
                              @endforeach    
                            @endif
                         
                          </select>
                          <p class="text-muted small">Set the Node id for your plan</p>
                        </div>

                        <div class="col-md-6">
                          <label for="plan_limit" class="form-label">Limit</label>
                          <input class="form-control" type="number" name="limit" id="plan_limit">
                          <p class="text-muted small">Set the 0 unlimited</p>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="cpu_limit">CPU Limit</label>
    
                            <div class="input-group">
                                <input type="text" id="cpu_limit" name="cpu_limit" class="form-control" value="0">
                                <span class="input-group-addon">%</span>
                            </div>
    
                            <p class="text-muted small">Set to <code>0</code> for unlimited.</p><p>
                        </p></div>

                        <div class="form-group col-xs-4">
                            <label for="plan_memory">Memory</label>
    
                            <div class="input-group">
                                <input type="text" id="plan_memory" name="memory" class="form-control" value="">
                                <span class="input-group-addon">MB</span>
                            </div>
    
                            <p class="text-muted small">Set to <code>0</code> for unlimited.</p>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="plan_disk_space">Disk Space</label>
    
                            <div class="input-group">
                                <input type="text" id="plan_disk_space" name="disk_space" class="form-control" value="">
                                <span class="input-group-addon">MB</span>
                            </div>
    
                            <p class="text-muted small">Set to <code>0</code> for unlimited.</p>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="plan_database_limit" class="control-label">Database Limit</label>
                            <div>
                                <input type="text" id="plan_database_limit" name="database_limit" class="form-control" value="0">
                            </div>
                            <p class="text-muted small">Total Allowed Databases</p>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="plan_allocation_limit" class="control-label">Allocation Limit</label>
                            <div>
                                <input type="text" id="plan_allocation_limit" name="allocation_limit" class="form-control" value="0">
                            </div>
                            <p class="text-muted small">Total Allowed Allocations</p>
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="plan_backup_limit" class="control-label">Backup Limit</label>
                            <div>
                                <input type="text" id="plan_backup_limit" name="backup_limit" class="form-control" value="0">
                            </div>
                            <p class="text-muted small">Total Allowed Backups</p>
                        </div>

                        <div class="col-md-12">
                            <label for="plan_description" class="form-label">Description</label>
                            <textarea name="description" id="plan_description" class="form-control" rows="4"></textarea>
                            <p class="text-muted small">A longer description of your plan.</p>
                        </div>

                        <div class="col-md-12">
                          <label for="plan_variables" class="form-label">Variables</label>
                          <textarea name="variables" id="plan_variables" class="form-control" rows="4" placeholder="BUNGEE_VERSION = latest&#10;SERVER_JARFILE = server.jar"></textarea>
                          <p class="text-muted small">Variable of your egg.</p>
                      </div>

                    </div>
                </div>
                <div class="modal-footer">
                    {!! csrf_field() !!}
                    <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>







<div class="modal fade" id="deletePlanModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <form id="create_game_id" action="{{ route('admin.billing.delete.plan') }}" method="POST">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Delete Plan</h4>
              </div>
              <div class="modal-body">
             
                    <strong class="text-center">Are you sure?</strong> 
            
              </div>
              <div class="modal-footer">
                  {!! csrf_field() !!}
                  <input type="hidden" id="delete_plan_id" name="plan_id" value="">
                  <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success btn-sm">Submit</button>
              </div>
          </form>
      </div>
  </div>
</div>


<script>  
  function deleteModal(id){
    document.getElementById('delete_plan_id').value = id;
  }
  
  </script>

@endsection
