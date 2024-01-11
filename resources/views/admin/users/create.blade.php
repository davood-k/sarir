@extends('welcome')

@section('mohtava')
    <ul>
    <li class="breadcrumb-item"><a href="">پنل مدیریت</a></li>
    <li class="breadcrumb-item "><a href="{{ route('users.index') }}">ليست كاربران</a></li>
    <li class="breadcrumb-item" active>ایجاد کاربر</li>
    </ul>

    <div class="row col-12">
        <div class="col-lg-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ایجاد کاربر</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post" action="{{ route('users.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">نام کاربر</label>
                            <input type="text" name="name" class="form-control" id="inputName"
                                placeholder="نام را وارد کنید">
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">ایمیل</label>
                            <input type="email" name="email" class="form-control" id="inputEmail3"
                                placeholder="ایمیل را وارد کنید">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">پسورد</label>
                            <input type="password" name="password" class="form-control" id="inputPassword3"
                                placeholder="پسورد را وارد کنید">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">تکرار پسورد</label>
                            <input type="password" name="password_confirmation" class="form-control" id="inputPassword3"
                                placeholder="دوباره پسورد را وارد کنید">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="verify" class="form-check-input" id='verify'>
                            <label for="form-check-label" for='verify'>اکانت فعال باشد</label>
                        </div>


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ثبت کابر</button>
                        <a href="{{ route('users.index') }} " class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endsection
