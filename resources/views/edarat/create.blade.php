@extends('welcome')

@section('mohtava')


    <div class="col-lg-8">
        {{-- @include('layouts.errors') --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">فرم ایجاد کاربر</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('edarat.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="inputname" class="col-sm-6 control-label">نام بخش</label>
                        <input type="text" name="name" class="form-control" id="inputname" placeholder="نام بخش را وارد کنید">
                    </div>
                    <div class="form-group">
                        <label for="inputaddress" class="col-sm-6 control-label">آدرس</label>
                        <input type="text" name="address" class="form-control" id="inputaddress" placeholder="آدرس را وارد کنید">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-6 control-label">شماره تماس</label>
                        <input type="text" name="password" class="form-control" id="inputPassword3" placeholder="شماره تماس را وارد کنید">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-6 control-label">رابط</label>
                        <input type="text" name="password_confirmation" class="form-control" id="inputPassword3" placeholder="نام رابط را وارد کنید">
                    </div>
                    <div class="form-group">
                        <label for="inputmoavent" class="col-sm-6 control-label">معاونت</label>
                        <input type="text" name="moavent" class="form-control" id="inputmoavent" placeholder="معاونت را وارد کنید">
                    </div>
                    
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">ثبت مشخصات</button>
                    <a href="" class="btn btn-default float-left">لغو</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>


@endsection