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
                        <a href="/" class="m-5">معرفی نامه</a>
                    </br>
                        <a href="/all" class="m-5">لیست ارتقاء</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
