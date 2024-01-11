@extends('welcome')

@section('mohtava')
    <style>
        .content {
            direction: rtl;
            text-align: right;
            !imprtant
        }
    </style>
    <section class="content">
        {{-- @include('admin.layouts.errors') --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">فرم نمایش کاربر</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="Get" action="{{ url('/person/create', $khadem->id) }}">

                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <label class="control-label d-flex">نام: {{ $khadem->namesr }}</label>
                                <label class="control-label d-flex">نام خانوادگی: {{ $khadem->familysr }}</label>
                                <label class="control-label d-flex">کدملی: {{ $khadem->codemsr }}</label>
                                <label class="control-label d-flex">محل خدمت: {{ $khadem->bkhademyarsr }}</label>
                                <label class="control-label d-flex">مدرک تحصیلی: {{ $khadem->madraksr }}</label>
                                <label class="control-label d-flex">تاریخ تولد: {{ $khadem->tdatesr }}</label>

                            </div>
                            <div class="col-3">
                                <label class="control-label d-flex">تاریخ شروع خدمت: {{ $khadem->dateshsr }}</label>
                                <label class="control-label d-flex">شماره همراه: {{ $khadem->mobilesr }}</label>
                                <label class="control-label d-flex">توضیحات: {{ $khadem->descriptionsr }}</label>
                            </div>
                            <div class="col-6">
                                <img class="m-auto rounded-circle shadow-4-strong" src="/dist/img/avatar5.png"
                                    alt="">
                            </div>


                            {{--                         
                        for enter page
                        https://mdbootstrap.com/previews/docs/latest/html/intros/intro-register-classic-form.html
                        --}}

                        </div>
                    </div>
                    <hr>
                    <li class="btn btn-info" style="border-radius: 8px;with: 80px;">نمرات خادمیار</li>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <label class="control-label d-flex">نمره کیفی: {{ $khadem->keifisr }}</label>
                                <label class="control-label d-flex">نمره سنوات: {{ $khadem->sanvatsr }}</label>
                                <label class="control-label d-flex">نمره انضباط: {{ $khadem->enzebatsr }}</label>
                                <label class="control-label d-flex">نمره ایثارگری: {{ $khadem->isarsr }}</label>
                                <label class="control-label d-flex">نمره تحصیلات: {{ $khadem->tahsilsr }}</label>
                                <label class="control-label d-flex">نمره نخبه: {{ $khadem->nokhbehsr }}</label>
                            </div>
                            <div class="col-3">
                            </div>
                            <div class="col-6">
                                <h2 class="p-5 font-italic text-center control-label d-flex text-success bg-success">تجمیع
                                    نمرات: {{ $khadem->tajmi }}</h2>
                            </div>


                        </div>
                    </div>

                    <div class="form-group">

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ویرایش کاربر</button>
                        <a href="" class="btn btn-warning float-left">بازگشت</a>
                    </div>
                    <!-- /.card-footer -->
            </form>
        </div>



    </section>
@endsection
