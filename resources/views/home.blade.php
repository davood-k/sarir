@extends('admin.layouts.app')
@section('content') 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __(' خوش آمدید') }}
                        </br>
                        <a href="/" class="mt-5" style="font-size: 25px;">لیست نامه های معرفی</a>
                    </br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
