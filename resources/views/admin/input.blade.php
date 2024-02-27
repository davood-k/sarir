@extends('welcome')

@section('mohtava')
    <form class="m-5" action="{{ route('saveImport') }}" method="post" enctype="multipart/form-data">
        @csrf

        <input class="btn btn-danger" type="file" name="file" class="form-control">

        <input class="btn btn-info" type="submit" value="upload" name="submit">

    </form>
    <div class="row col-12 m-2">
        @include('admin.layouts.errors')
    </div>
    <!-- form start -->
    <form class="form-group " method="post" action="{{ route('promotion') }}">
        @csrf
        <div class="row d-flex mt-5">

            <div class="row col-12">
                <div class="form-group mr-4">
                    <label for="moavenat">
                        <select name="moavenat" id="moavenat" class="form-select">
                            <option value="">معاونت</option>
                        </select>
                    </label>
                    <label for="bkhademyarsr" class="mr-3">
                        <select name="bkhademyarsr" id="bkhademyarsr">
                            <option value="">اداره</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="codemelli" type="text" class="form-control form-control-sm " placeholder="شماره ملی"
                        value="{{ old('codemelli') }}">
                    <label for="codemelli" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>

            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="fname" type="text" class="form-control form-control-sm" placeholder="نام"
                        value="{{ old('fname') }}">
                    <label for="sanvatsr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>

            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="lname" type="text" class="form-control form-control-sm" placeholder="نام خانوادگی"
                        value="{{ old('lname') }}">
                    <label for="lname" class="mr-2 input-required" style="width : 40px">*</label>

                </div>
            </div>
        </div>

        <div class="row d-flex">

            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="dateshsr" type="text" class="form-control form-control-sm" placeholder="تاریخ شروع خدمت"
                        value="{{ old('dateshsr') }}">
                    <label for="dateshsr" class="mr-2 input-required" style="width : 40px">*</label>

                </div>
            </div>

            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="tdatesr" type="text" class="form-control form-control-sm" placeholder="تاریخ تولد"
                        value="{{ old('tdatesr') }}">
                    <label for="tdatesr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>

            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="mobilesr" type="text" class="form-control form-control-sm" placeholder="شماره همراه"
                        value="{{ old('mobilesr') }}">
                    <label for="mobilesr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
        </div>
        <div class="row d-flex">
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="sanvatsr" type="text" class="form-control form-control-sm" placeholder="سنوات">
                    <label for="sanvatsr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="enzebatsr" type="text" class="form-control form-control-sm" placeholder="انضباط">
                    <label for="enzebatsr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="keifisr" type="text" class="form-control form-control-sm" placeholder="کیفی">
                    <label for="keifisr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="isarsr" type="text" class="form-control form-control-sm" placeholder="ایثارگری">
                    <label for="isarsr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
        </div>
        <div class="row d-flex">
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="tahsilsr" type="text" class="form-control form-control-sm" placeholder="تحصیلات">
                    <label for="tahsilsr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="nokhbehsr" type="text" class="form-control form-control-sm" placeholder="نخبه">
                    <label for="nokhbehsr" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
            <div class="col-2 mr-5">
                <div class="form-group d-flex">
                    <input name="tajmi" type="text" class="form-control form-control-sm" placeholder="تجمیع">
                    <label for="tajmi" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>
        </div>
        <div class="row col-12 d-flex">
            <div class="mr-5">
                <select class="form-control mr-3 " id="type" name="madraksr">
                    <option value="دیپلم">
                        دیپلم</option>
                    <option value="فوق دیپلم">
                        فوق دیپلم</option>
                    <option value="لیسانس">
                        لیسانس</option>
                    <option value="فوق لیسانس">
                        فوق لیسانس</option>
                    <option value="دکتری">
                        دکتری</option>
                </select>
            </div>
            <input class=" mr-4 form-control form-control-sm w-50" name="descriptionsr" type="text"
                placeholder="توضیحات در صورت نیاز " value="{{ old('descriptionsr') }}">
           
        </div>
        <div style="margin-right: 50px;padding:15px 5px 15px 5px;width:200px;" class="col-2 d-flex mt-3 ">
            <div class="form-check p-0">
                <input class="form-check-input" type="radio" name="level" id="shdaradi" value="0">
                <label class="form-check-label" for="level">عادی</label>
            </div>
            <div class="form-check mr-2">
                <input class="form-check-input" type="radio" name="level" id="sherkatDarAzsr" value="1">
                <label class="form-check-label" for="level">آزمون</label>
            </div>
            <div class="form-check mr-2 pl-2">
                <input class="form-check-input" type="radio" name="level" id="ShDarComision" value="2">
                <label class="form-check-label" for="level">کمیسیون</label>
            </div>
        </div>


        <button type="submit" class="btn btn-primary m-3">ثبت</button>
        <a href="/all" class="btn btn-sm btn-info">لیست ارتقاء</a>
    </form>
    <script type="text/javascript">
        var data = {
            states: [{
                    moavenat: "امیریه تولیت",
                    districts: [
                        "امیریه تولیت",
                    ],
                },
                {
                    moavenat: "سازمان فرهنگی",
                    districts: [
                        "علمی",
                    ],
                },
                {
                    moavenat: "نیابت",
                    districts: [
                        "نیابت",
                    ],
                },
                {
                    moavenat: "همکاران",
                    districts: [
                        "همکاران",
                    ],
                },
                {
                    moavenat: "اماکن",
                    districts: [
                        "کتب انوار",
                        "گروه ویژه",
                        "خواهران خدمه",
                        "انتظامات حریم",
                        "انتظامات صحن ها",
                        "انتظامات رواق ها",
                        "تشریفات آئین ها و مناسبت ها",
                        "زلال رضوان",
                        "شمیم رضوان",
                        "صحافی",
                        "فنی",
                        "کفشداری (میزبان)",
                        "فراشی (میزبان)",
                        "خدام (میزبان)",
                        "دربانی (میزبان)",
                    ],
                },
                {
                    moavenat: "تبلیغات",
                    districts: [
                        "رواق کودک",
                        "پایگاه ها",
                        "پاسخگویی",
                        "مراسم و آئین ها",
                        "زائرین غیر ایرانی",
                        "دارالقرآن",
                        "برنامه ریزی",
                        "امور عمومی",
                        "نعیم رضوان",
                        "نسیم رضوان",
                        "طرح و برنامه",
                        "صندلی چرخدار",
                        "مهمانسرای حر",
                        "مهمانسرای غدیر",
                        "روشنایی",
                        "فنی",
                        "آرایشگر",
                        "نظارت بر خدمات نظافت",
                        "گل آرائی",
                        "نظارت فرش"
                    ],
                },

            ],
        };

        $(document).ready(function() {
            const selectState = document.getElementById("moavenat");
            const selectDistrict = document.getElementById("bkhademyarsr");
            selectDistrict.disabled = true;

            //Add moavenat Value to moavenat Select option
            data.states.forEach((value) => {
                selectState.appendChild(createOption(value.moavenat, value.moavenat));
            });

            selectState.addEventListener("change", function(e) {
                selectDistrict.disabled = false;
                data.states.forEach((detail, index) => {
                    //console.log(data.states[index].districts);
                    if (detail.moavenat == e.target.value) {
                        selectDistrict.innerHTML = "";
                        selectDistrict.append(createOption(" بخش خدمتی", ""));
                        data.states[index].districts.forEach((moarefi) => {
                            selectDistrict.append(createOption(moarefi, moarefi));
                        });
                    }
                });
            });

            //Create New Option Tag With Value
            function createOption(displayMember, valueMember) {
                const newOption = document.createElement("option");
                newOption.value = valueMember;
                newOption.text = displayMember;
                return newOption;
            }
        });
    </script>
@endsection
