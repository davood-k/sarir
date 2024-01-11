@extends('welcome')

@section('mohtava')
    <div class="col-sm-12 main">
        <div class="page-header head-section">
            <h2>تعریف وظیفه</h2>
        </div>
        <form class="form-horizontal" action="{{ route('duty.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include('Admin.layouts.errors')
            <div class="row col-sm-12">
                <div class="form-group">
                    <div class="">
                        <label for="title" class="control-label">عنوان وظیفه</label>
                        <input type="text" class="form-control" name="title" id="title"
                            placeholder="عنوان را وارد کنید" value="{{ old('title') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="date" class="control-label">تاریخ</label>
                        <input type="text" class="form-control" name="date" id="date"
                            placeholder="عنوان را وارد کنید" value="{{ old('date') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="numbers" class="control-label">تعداد</label>
                        <input type="text" class="form-control" name="numbers" id="numbers"
                            placeholder="عنوان را وارد کنید" value="{{ old('numbers') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <label for="span" class="control-label">بازه زمانی</label>
                    <input type="text" class="form-control" name="span" id="span"
                        placeholder="عنوان را وارد کنید" value="{{ old('span') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label for="expires" class="control-label">انقضاء</label>
                    <input type="text" class="form-control" name="expires" id="expires"
                        placeholder="عنوان را وارد کنید" value="{{ old('expires') }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    اهمیت
                    <select class="form-control w-75 mr-5 " id="type" name="importantrange">
                        <option value="5">خیلی مهم</option>
                        <option value="4">مهم</option>
                        <option value="3">متوسط</option>
                        <option value="2">کم</option>
                        <option value="1">خیلی کم</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9">
                    <label for="descriptions" class="control-label">توضیحات کوتاه</label>
                    <textarea rows="5" class="form-control" name="descriptions" id="descriptions" placeholder="توضیحات را وارد کنید">{{ old('descriptions') }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-danger">ارسال</button>
                </div>
            </div>
        </form>
    </div>
@endsection
