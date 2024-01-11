@extends('welcome')

@section('script')
    <script>
        $(document).ready(function() {
            $('#permission_id').selectpicker();
        })
    </script>
@endsection

@section('mohtava')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div class="page-header head-section">
            <h2>ویرایش معرفی</h2>
        </div>
        <form class="form-horizontal" method="post" action="{{ route('moarefi.update', $defi->id) }}">
            @csrf
            @method('PUT')
            @include('Admin.layouts.errors')
            <div class="form-group row d-flex">
                <div class="col-sm-2">
                    <label for="fname" class="control-label">نام</label>
                    <input type="text" class="form-control" name="fname" id="fname" placeholder="نام را وارد کنید"
                        value="{{ $khadems->fname }}">
                </div>
                <div class="col-sm-2">
                    <label for="lname" class="control-label">فامیل</label>
                    <input type="text" class="form-control" name="lname" id="lname"
                        placeholder="عنوان را وارد کنید" value="{{ $khadems->lname }}">
                </div>
                <div class="col-sm-3">
                    <label for="codemelli" class="control-label">کدملی</label>
                    <input type="text" class="form-control" name="codemelli" id="codemelli"
                        placeholder="عنوان را وارد کنید" value="{{ $khadems->codemelli }}">
                </div>
            </div>
            <div class="form-group row d-flex">

                <div class="col-sm-2">
                    <label for="shletter" class="control-label">شماره نامه</label>
                    <input type="text" class="form-control" name="shletter" id="shletter"
                        placeholder="عنوان را وارد کنید" value="{{ $defi->sh_letter }}">
                </div>

                <div class="col-sm-2">
                    <label for="dateletter" class="control-label">تاریخ نامه</label>
                    <input type="text" class="form-control" name="dateletter" id="dateletter"
                        placeholder="عنوان را وارد کنید" value="{{ $defi->date_letter }}">
                </div>

                <div class="col-sm-2">
                    <label for="moarefi" class="control-label">معرفی</label>
                    <input type="text" class="form-control" name="moarefi" id="moarefi"
                        placeholder="عنوان را وارد کنید" value="{{ $defi->moarefi }}">
                </div>

                <div class="col-sm-2">
                    <label for="moavenat" class="control-label">معاونت</label>
                    <input type="text" class="form-control" name="moavenat" id="moavenat"
                        placeholder="عنوان را وارد کنید" value="{{ $defi->moavenat }}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label for="tozih" class="control-label">توضیحات کوتاه</label>
                    <textarea rows="3" class="form-control" name="tozih" id="tozih" placeholder="توضیحات را وارد کنید"
                        value="{{ $defi->tozih }}">{{ $defi->tozih }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-danger">ارسال</button>
                    <a href="/" class="btn btn-primary mr-3">بازگشت</a>
                </div>
            </div>
        </form>
    </div>
@endsection
