@extends('welcome')

@section('mohtava')
    {{-- @include('admin.layouts.errors') --}}

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">فرم ویرایش کاربر</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" method="post" action='/persons/{{ $khadem->id }}/update'>
            @csrf
            @method('put')
            <div class="card-body">
                <div class="row col-12 d-flex">
                    <div class="col-5">
                        <div class="form-group">
                            <label for="namesr" class="col-12 control-label d-flex">نام</label>
                            <input type="text" name="namesr" class="form-control" id="namesr"
                                value="{{ $khadem->namesr }}">
                        </div>
                        <div class="form-group">
                            <label for="codemsr" class="col-12 control-label">کدملی</label>
                            <input type="text" name="codemsr" class="form-control" id="codemsr"
                                value="{{ $khadem->codemsr }}">
                        </div>
                        <div class="form-group">
                            <label for="dateshsr" class="col-12 control-label">تاریخ شروع خدمت</label>
                            <input type="text" name="dateshsr" class="form-control" id="dateshsr"
                                value="{{ $khadem->dateshsr }}">
                        </div>
                        <div class="form-group">
                            <label for="mobilesr" class="col-12 control-label">شماره همراه</label>
                            <input type="text" name="mobilesr" class="form-control" id="mobilesr"
                                value="{{ $khadem->mobilesr }}">
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <label for="family" class="col-12 control-label">فامیل</label>
                            <input type="text" name="family" class="form-control" id="family"
                                value="{{ $khadem->familysr }}">
                        </div>

                        <div class="form-group">
                            <label for="bkhademyarsr" class="col-12 control-label">محل خدمت</label>
                            <input type="text" name="bkhademyarsr" class="form-control" id="bkhademyarsr"
                                value="{{ $khadem->bkhademyarsr }}">
                        </div>
                        <div class="form-group">
                            <label for="moavenat" class="col-12 control-label">معاونت</label>
                            <input type="text" name="moavenat" class="form-control" id="moavenat"
                                value="{{ $khadem->moavenat }}">
                        </div>
                        <div class="form-group">
                            <label for="madraksr" class="col-12 control-label">مدرک تحصیلی</label>
                            <select class="form-control" id="madraksr" name="madraksr">
                                <option value="دیپلم" {{($khadem->madraksr== 'دیپلم')? 'selected':'' }}>دیپلم</option>
                                <option value="کاردانی" {{($khadem->madraksr== 'کاردانی')? 'selected':'' }}>کاردانی</option>
                                <option value="لیسانس" {{($khadem->madraksr== 'لیسانس')? 'selected':'' }}>لیسانس</option>
                                <option value="فوق لیسانس" {{($khadem->madraksr== 'فوق لیسانس')? 'selected':'' }}>فوق لیسانس</option>
                                <option value="دکتری" {{($khadem->madraksr== 'دکتری')? 'selected':'' }}>دکتری</option>
                             </select>
                        </div>
                        <div class="form-group">
                            <label for="tdatesr" class="col-12 control-label">تاریخ تولد</label>
                            <input type="text" name="tdatesr" class="form-control" id="tdatesr"
                                value="{{ $khadem->tdatesr }}">
                        </div>

                    </div>
                    
                </div>

                <div class="row">
                    <div class="form-group col-9">
                        <label for="descriptionsr" class=" control-label">توضیحات</label>
                        <input type="text" name="descriptionsr" class="form-control" id="descriptionsr"
                            value="{{ $khadem->descriptionsr }}">
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">ثبت ویرایش</button>
                <a href="/all" class="btn btn-warning float-left">بازگشت</a>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection
