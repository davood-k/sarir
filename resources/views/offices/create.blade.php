@extends('welcome')

@section('mohtava')
    <div class="col-sm-12 main">
        <div class="page-header head-section">
            <h2>ایجاد بخش</h2>
        </div>
        <form class="form-horizontal" action="{{ route('informationOffice.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('Admin.layouts.errors')
            <div class="row col-sm-12">
                <div class="form-group">
                    <div class="">
                        <label for="offices" class="control-label">نام بخش</label>
                        <input type="text" class="form-control" name="offices" id="offices"
                            placeholder="عنوان را وارد کنید" value="{{ old('offices') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="personsRelation" class="control-label">نام رابط</label>
                        <input type="text" class="form-control" name="personsRelation" id="personsRelation"
                            placeholder="نام رابط را وارد کنید" value="{{ old('personsRelation') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="numbers" class="control-label">داخلی</label>
                        <input type="text" class="form-control" name="numbers" id="numbers"
                            placeholder="شماره داخلی بخش را وارد کنید" value="{{ old('numbers') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="mobiles" class="control-label">شماره همراه</label>
                        <input type="text" class="form-control" name="mobiles" id="mobiles"
                            placeholder="شماره همراه را وارد کنید" value="{{ old('mobiles') }}">
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-9">
                    <label for="address" class="control-label">آدرس</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="آدرس را وارد کنید"
                        value="{{ old('address') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4">
                    نوع کشیک
                    <select class="form-control w-75 mr-5 " id="post" name="post">
                        <option value="2">چرخشی</option>
                        <option value="1">ثابت</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4">
                    ساعت خدمتی
                    <select class="form-control w-75 mr-5 " id="timeServices" name="timeServices">
                        <option value="1">6 ساعته</option>
                        <option value="2">8 ساعته</option>
                        <option value="3">12 ساعته</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9">
                    <label for="description" class="control-label">توضیحات کوتاه</label>
                    <textarea rows="5" class="form-control" name="description" id="description" placeholder="توضیحات را وارد کنید">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-danger">ارسال</button>
                    <a href="/admin/informationOffice" class="btn btn-info mr-5">بازگشت</a>
                </div>
            </div>
        </form>
    </div>
@endsection
