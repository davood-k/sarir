@extends('welcome')

@section('mohtava')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div class="page-header head-section">
            <h2>ویرایش وظایف</h2>
        </div>

        <form class="form-horizontal" action="{{ route('informationOffice.update', $informationOffice->id) }}" method="post"
            enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            @include('Admin.layouts.errors')
            <div class="row col-sm-12">
                <div class="form-group">
                    <div class="">
                        <label for="offices" class="control-label input-required">نام بخش</label>
                        <input type="text" class="form-control" name="offices" id="offices"
                            placeholder="عنوان را وارد کنید" value="{{ $informationOffice->offices }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="personsRelation" class="control-label input-required">نام رابط</label>
                        <input type="text" class="form-control" name="personsRelation" id="personsRelation"
                            placeholder="عنوان را وارد کنید" value="{{ $informationOffice->personsRelation }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="numbers" class="control-label input-required">داخلی</label>
                        <input type="text" class="form-control" name="numbers" id="numbers"
                            placeholder="عنوان را وارد کنید" value="{{ $informationOffice->numbers }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="mobiles" class="control-label input-required">موبایل</label>
                        <input type="text" class="form-control" name="mobiles" id="mobiles"
                            placeholder="عنوان را وارد کنید" value="{{ $informationOffice->mobiles }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <label for="address" class="control-label input-required">آدرس</label>
                    <input type="text" class="form-control" name="address" id="address"
                        placeholder="عنوان را وارد کنید" value="{{ $informationOffice->address }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4">
                    نوع کشیک
                    <select class="form-control w-75 mr-5 " id="post" name="post">
                        <option value="2" {{ $informationOffice->post == '2' ? 'selected' : '' }}>چرخشی</option>
                        <option value="1" {{ $informationOffice->post == '1' ? 'selected' : '' }}>ثابت</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4">
                    ساعت خدمتی
                    <select class="form-control w-75 mr-5 " id="timeServices" name="timeServices">
                        <option value="1" {{ $informationOffice->timeServices == '1' ? 'selected' : '' }}>6 ساعته</option>
                        <option value="2" {{ $informationOffice->timeServices == '2' ? 'selected' : '' }}>8 ساعته</option>
                        <option value="3" {{ $informationOffice->timeServices == '3' ? 'selected' : '' }}>12 ساعته</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9">
                    <label for="description" class="control-label">توضیحات کوتاه</label>
                    <textarea rows="5" class="form-control" name="description" id="description" placeholder="توضیحات را وارد کنید"> {{ $informationOffice->description }} </textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-danger">ذخیره</button>
                </div>
            </div>
        </form>
    </div>
@endsection
