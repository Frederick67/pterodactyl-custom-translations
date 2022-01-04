{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}

@extends('layouts.admin')

@section('title')
    Newsletter
@endsection

@section('content-header')
    <h1>Newsletter<small>Send an email out to all users that opt-in to the newsletter.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Newsletter</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Email Information</h3>
                </div>

                <div class="box-body row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="pSubject">Subject Line</label>
                            <input type="text" class="form-control subject" id="pSubject" name="subject" value="{{ old('subject') }}" placeholder="New panel update">
                        </div>
                    </div>
                    
                    <div class="form-group col-xs-12">
                        <label for="pDescription" class="control-label">Description</label>
                        <div>
                            <textarea name="description" id="pDescription" rows="4" class="form-control body" placeholder="We have now added a newsletter feature to our panel!
Click the button below to view it in the Admin Panel."></textarea>
                        </div>
                    </div>
                    
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="pURL">Button Text</label>
                            <input type="text" class="form-control urltext" id="pBText" name="btext" value="{{ old('btext') }}" placeholder="Go to Sender">
                        </div>
                        <div class="form-group">
                            <label for="pBLink">Button Link</label>
                            <input type="text" class="form-control urllink" id="pBLink" name="blink" value="{{ old('blink') }}" placeholder="{{ route('admin.newsletter.index') }}">
                        </div>
                    </div>
                    
                    <div class="form-group col-xs-12">
                    {!! csrf_field() !!}
                    <button id="sendButton" class="btn btn-sm btn-success pull-right">Send</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent

    <script>
    
        function sendNewsletter() {
            if($(".urltext").val()) {
              var $urltext = $(".urltext").val();
              var $urllink = $(".urllink").val();
            } else {
              var $urltext = "none";
              var $urllink = "none";
            }
            swal({
                type: 'info',
                title: 'Send Newsletter',
                text: 'Click "Confirm" to send the email.',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'POST',
                    url: '/admin/newsletter/send',
                    headers: { 'X-CSRF-Token': $('input[name="_token"]').val() },
                    data: { 'subject': $(".subject").val(), 'body': $(".body").val(), 'urltext': $urltext, 'urllink': $urllink }
                }).fail(function (jqXHR) {
                    showErrorDialog(jqXHR, 'send');
                }).done(function () {
                    swal({
                        title: 'Success',
                        text: 'The newsletter was sent successfully.',
                        type: 'success'
                    });
                });
            });
        }
        
        function determineModal() {
          if(!($(".subject").val())) {
            missingFields("subject")
          } else if(!($(".body").val())) {
            missingFields("body")
          } else {
            sendNewsletter()
          }
        }
        
        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            var errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function (i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }
            swal({
                title: 'Whoops!',
                text: 'An error occurred while attempting to ' + verb + ' newsletter: ' + errorText,
                type: 'error'
            });
        }
        
        function missingFields(missingField) {
            swal({
                title: 'Missing field',
                text: 'Please fill out the ' + missingField + " before sending.",
                type: 'error'
            });
        }
        
        $(document).ready(function () {
        $('#sendButton').on('click', determineModal);
        });
    </script>
@endsection
