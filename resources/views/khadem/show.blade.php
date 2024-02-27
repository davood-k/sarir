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
                <h3 class="card-title">فرم نمایش اطلاعات کاربر</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            {{-- <form class="form-horizontal" > --}}

            <div class="card-body">
                <div class="form-group">

                    <div class="row d-flex">
                        <div class="col-4 mt-3 d-flex">
                            <label class="control-label m-auto">نام: </label>
                            <input style="background-color: #fff;" type="text"
                                class="col-7 form-control saveData border-0 namesr" value="{{ $khadem->namesr }}" readonly
                                ondblclick="setFieldStatus(this, false)">
                        </div>
                        <div class="col-4 mt-3 d-flex">
                            <label class="control-label m-auto">نام خانوادگی: </label>
                            <input style="background-color: #fff;" type="text"
                                class="mr-2 col-7 form-control saveData border-0 familysr" value="{{ $khadem->familysr }}"
                                readonly ondblclick="setFieldStatus(this, false)">
                        </div>
                        <div class="col-4 mt-3 d-flex">
                            <label class="control-label m-auto">کدملی: </label>
                            <input style="background-color: #fff;" type="text"
                                class="fontsbLotussmes mr-2 col-7 form-control saveData border-0 codemsr"
                                value="{{ $khadem->codemsr }}" readonly ondblclick="setFieldStatus(this, false)">
                        </div>
                        <div class="col-4 mt-3 d-flex">
                            <label class="control-label m-auto">محل خدمت: </label>
                            <input style="background-color: #fff;" type="text"
                                class="mr-2 col-7 form-control saveData border-0 bkhademyarsr"
                                value="{{ $khadem->bkhademyarsr }}" readonly ondblclick="setFieldStatus(this, false)">
                        </div>
                        <div class="col-4 mt-3 d-flex">
                            <label class="control-label m-auto">مدرک تحصیلی: </label>
                            <input style="background-color: #fff;" type="text"
                                class="mr-2 col-7 form-control saveData border-0 madraksr" value="{{ $khadem->madraksr }}"
                                readonly ondblclick="setFieldStatus(this, false)">
                        </div>
                        <div class="mt-3 d-flex">
                            <label class="control-label m-auto">تاریخ تولد: </label>
                            <input style="background-color: #fff;" type="text"
                                class="fontsbLotussmes mr-2 col-7 form-control saveData border-0 tdatesr"
                                value="{{ $khadem->tdatesr }}" readonly ondblclick="setFieldStatus(this, false)">
                        </div>

                    </div>
                    <div class="row d-flex">
                        <div class="mt-3 d-flex">
                            <label class="control-label m-auto">تاریخ شروع خدمت: </label>
                            <input style="background-color: #fff;" type="text"
                                class="fontsbLotussmes mr-2 col-7 form-control saveData border-0 dateshsr"
                                value="{{ $khadem->dateshsr }}" readonly ondblclick="setFieldStatus(this, false)">
                        </div>
                        <div class="mt-3 d-flex">
                            <label class="control-label m-auto">شماره همراه: </label>
                            <input style="background-color: #fff;" type="text"
                                class="fontsbLotussmes mr-2 col-7 form-control saveData border-0 mobilesr"
                                value="{{ $khadem->mobilesr }}" readonly ondblclick="setFieldStatus(this, false)">
                        </div>
                    </div>
                    <div class="mt-3 d-flex">
                        <label style="font-size: 20px;" class="control-label mr-0">توضیحات: </label>
                        <input style="background-color: #fff;" type="text"
                            class="fontsbLotussmes mr-2 col-7 text-right form-control saveData border-0 descriptionsr"
                            value="{{ $khadem->descriptionsr }}" readonly ondblclick="setFieldStatus(this, false)">
                    </div>
                </div>
                <hr>
                <h3 class="m-4" style="border-radius: 8px;with: 80px;">نمرات خادمیاری</h3>

                <div class="form-group">
                    <div class="row d-flex">
                        <div class="col-3">
                            <div class="d-flex">
                                <label class="control-label m-auto">نمره کیفی: </label>
                                <input style="background-color: #fff;" type="text"
                                    class="fontsbLotussmes col-3 form-control saveData border-0 keifisr"
                                    value="{{ $khadem->keifisr }}" readonly ondblclick="setFieldStatus(this, false)">
                            </div>
                            <div class="d-flex mt-2">
                                <label class="control-label m-auto">نمره سنوات: </label>
                                <input style="background-color: #fff;" type="text"
                                    class="fontsbLotussmes col-3 form-control saveData border-0 sanvatsr"
                                    value="{{ $khadem->sanvatsr }}" readonly ondblclick="setFieldStatus(this, false)">
                            </div>
                            <div class="d-flex mt-2">
                                <label class="control-label m-auto">نمره انضباط: </label>
                                <input style="background-color: #fff;" type="text"
                                    class="fontsbLotussmes col-3 form-control saveData border-0 enzebatsr"
                                    value="{{ $khadem->enzebatsr }}" readonly ondblclick="setFieldStatus(this, false)">
                            </div>
                            <div class="d-flex mt-2">
                                <label class="control-label m-auto">نمره ایثارگری: </label>
                                <input style="background-color: #fff;" type="text"
                                    class="fontsbLotussmes col-3 form-control saveData border-0 isarsr"
                                    value="{{ $khadem->isarsr }}" readonly ondblclick="setFieldStatus(this, false)">
                            </div>
                            <div class="d-flex mt-2">
                                <label class="control-label m-auto">نمره تحصیلات: </label>
                                <input style="background-color: #fff;" type="text"
                                    class="fontsbLotussmes col-3 form-control saveData border-0 tahsilsr"
                                    value="{{ $khadem->tahsilsr }}" readonly ondblclick="setFieldStatus(this, false)">
                            </div>
                            <div class="d-flex mt-2">
                                <label class="control-label m-auto">نمره نخبه: </label>
                                <input style="background-color: #fff;" type="text"
                                    class="fontsbLotussmes col-3 form-control saveData border-0 nokhbehsr"
                                    value="{{ $khadem->nokhbehsr }}" readonly ondblclick="setFieldStatus(this, false)">
                            </div>
                        </div>
                        <div class="col-3">
                        </div>
                        <div class="col-6">
                            <label class="control-label mt-2">تجمیع نمرات: </label>
                            <input style="background-color: rgb(112, 129, 109); color:#fff;" type="text"
                                class="fontsbLotussmes col-3 form-control saveData border-0 text-center tajmi"
                                value="{{ $khadem->tajmi }}" readonly ondblclick="setFieldStatus(this, false)">
                            <?php
                            $temp = \App\Khadem::find($khadem->id);
                            ?>
                            @foreach ($temp->azmoons as $item)
                                <label class="control-label mt-2">نمره آزمون: </label>
                                <input style="background-color: rgb(112, 129, 109); color:#fff;" type="text"
                                    class="fontsbLotussmes col-3 form-control saveData border-0 text-center nomrehAzmoonsr"
                                    value="{{ $item->nomrehAzmoonsr }}" readonly
                                    ondblclick="setFieldStatus(this, false)">
                            @endforeach
                        </div>


                    </div>
                </div>

                <div class="form-group">

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{-- <button type="submit" class="btn btn-info">ویرایش کاربر</button> --}}
                    <a href="{{ URL::previous() }}" class="btn btn-warning float-left">بازگشت</a>
                </div>
                <!-- /.card-footer -->
                {{-- </form> --}}
            </div>

            <script>
                function setFieldStatus(item, status) {
                    $(item).attr('readonly', status);
                }

                $('.saveData').on('keyup', function(e) {
                    if (e.key == 'Enter') {
                        var value = $(this).val();
                        var className = this.classList[this.classList.length - 1];

                        //send to database
                        var url = '/persons/' + {{ $khadem->id }} + '/update/' + className + '/' + value;

                        $.get(url, function(result) {
                            if (result != null) {
                                alert('تغییرات اعمال گردید.');
                                setFieldStatus(this, true);
                            } else {
                                alert('error');
                            }
                        });

                        setFieldStatus(this, true);
                    }
                });
            </script>

    </section>
@endsection
