@extends('welcome')

@section('mohtava')
    <form class="m-5" action="{{ route('Importkhademyar') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input class="btn btn-danger" type="file" name="file" class="form-control">
        <input class="btn btn-info" type="submit" value="upload" name="submit">
    </form>
    <div class="row col-12 m-2">
        @include('admin.layouts.errors')
    </div>
    <!-- form start -->
    <form class="form-group " method="post" action="{{ route('sendpersons') }}">
        @csrf
        <div class="row d-flex mt-5">

            <div class="col-3 mr-3">
                <div class="form-group d-flex">
                    <input name="codemelli" type="text" class="form-control form-control-sm " placeholder="شماره ملی"
                        value="{{ old('codemelli') }}">
                    <label for="codemelli" class="mr-2 input-required" style="width : 40px">*</label>
                </div>
            </div>

            <div class="col-3 mr-5">
                <div class="form-group d-flex">
                    <input name="fname" type="text" class="form-control form-control-sm" placeholder="نام">

                </div>
            </div>

            <div class="col-3 mr-5">
                <div class="form-group d-flex">
                    <input name="lname" type="text" class="form-control form-control-sm" placeholder="نام خانوادگی">
                    <label for="codemelli" class="mr-2 input-required" style="width : 40px">*</label>

                </div>
            </div>
        </div>

        <div class="row d-flex">

            <div class="col-3 mr-3">
                <div class="form-group d-flex">
                    <input name="shletter" type="text" class="form-control form-control-sm" placeholder="شماره نامه">
                    <label for="codemelli" class="mr-2 input-required" style="width : 40px">*</label>

                </div>
            </div>

            <div class="col-3 mr-5">
                <div class="form-group d-flex">
                    <input name="dateletter" type="text" class="form-control form-control-sm" placeholder="تاریخ نامه">
                    <label for="codemelli" class="mr-2 input-required" style="width : 40px">*</label>

                </div>
            </div>

            <div class="col-2 d-flex">
                <div class="form-check mr-2">
                    <input class="form-check-input" type="radio" name="gender" id="inlineCheckbox1" value="1"
                        checked>
                    <label class="form-check-label" for="inlineCheckbox1">آقا</label>
                </div>
                <div class="form-check mr-2">
                    <input class="form-check-input" type="radio" name="gender" id="inlineCheckbox2" value="2">
                    <label class="form-check-label" for="inlineCheckbox2">خانم</label>
                </div>

            </div>
            <div class="row col-12  ">
                <div class="form-group mr-4">
                    <label for="moavenat">
                        <select name="moavenat" id="moavenat" class="form-select">
                            <option value="">معاونت</option>
                        </select>
                    </label>
                    <label for="moarefi" class="mr-3">
                        <select name="moarefi" id="moarefi">
                            <option value="">اداره</option>
                        </select>
                    </label>
                </div>
            </div>


        </div>

        {{-- <div class="row m-3">
            <input class="form-control form-control-sm w-75" name="molahezat" type="text"
                placeholder="در صورت تمایل ملاحظات را یادداشت فرمائید">
        </div> --}}

        <div class="row m-3">
            <input class="form-control form-control-lg w-50" name="tozih" type="text"
                placeholder="در صورت نیاز درج دستور نامه">
        </div>
        <button type="submit" class="btn btn-primary m-3">ثبت</button>
        <a href="/" class="btn btn-info">مشاهده لیست معرفی ها</a>
    </form>

    <script type="text/javascript">
        var data = {
            states: [{
                    moavenat: "اماکن",
                    districts: [
                        "کتب انوار",
                        "گروه ویژه",
                        "خواهران خدمه",
                        "انتظامات حریم",
                        "انتظامات صحن ها",
                        "انتظامات صحن ها و حریم",
                        "انتظامات رواق ها",
                        "تشریفات آئین ها و مناسبت ها",
                        "زلال رضوان",
                        "شمیم رضوان",
                        "صحافی",
                        "کفشداری (میزبان)",
                        "فراشی (میزبان)",
                        "خدام (میزبان)",
                        "دربانی (میزبان)",
                        "طرح و برنامه",
                    ],
                },
                {
                    moavenat: "خدمات زائرین",
                    districts: [
                        "چایخانه",
                        "صندلی چرخدار",
                        "پیداشدگان",
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
                    ],
                },
                {
                    moavenat: "ستادی",
                    districts: [
                        "نعیم رضوان",
                        "نسیم رضوان",
                        "طرح و برنامه",
                    ],
                },
                {
                    moavenat: "بنیاد کرامت",
                    districts: [
                        "امور بانوان",
                        "مرکز خادمیاری",
                    ],
                },
                {
                    moavenat: "یگان صیانت",
                    districts: [
                        "یگان صیانت",
                    ],
                },
                {
                    moavenat: "مشاوره",
                    districts: [
                        "مشاوره",
                    ],
                },
                {
                    moavenat: "مرکز ارتباطات و رسانه",
                    districts: [
                        "پاسخگویی 138",
                        "رسانه",
                        "افکارسنجی",
                        "ارتباط با مخاطبین",
                    ],
                },
                {
                    moavenat: "موسسه موقوفه",
                    districts: [
                        "زائرسرا",
                        "زائرشهر",
                    ],
                },
                {
                    moavenat: "بازرسی",
                    districts: [
                        "بازرسی",
                    ],
                },
                {
                    moavenat: "حراست",
                    districts: [
                        "حراست",
                    ],
                },
                {
                    moavenat: "نذورات",
                    districts: [
                        "نذورات",
                    ],
                },
                {
                    moavenat: "فناوری اطلاعات",
                    districts: [
                        "فناوری اطلاعات",
                    ],
                },
                {
                    moavenat: "دارالشفاء",
                    districts: [
                        "دارالشفاء",
                    ],
                },
                {
                    moavenat: "امور خدام",
                    districts: [
                        "جذب و سازماندهی",
                        "ارزیابی و توانمند سازی",
                    ],
                },
                {
                    moavenat: "گزینش",
                    districts: [
                        "گزینش",
                    ],
                },
                {
                    moavenat: "بحران",
                    districts: [
                        "بحران و پدافند عامل",
                    ],
                },
                {
                    moavenat: "علمی",
                    districts: [
                        "خادمیاران علمی",
                        "ستادی سازمان علمی و فرهنگی",
                    ],
                },
                {
                    moavenat: "آموزش",
                    districts: [
                        "آموزش",
                    ],
                },
                {
                    moavenat: "سازمان فرهنگی",
                    districts: [
                        "کتابخانه",
                        "مرکز قرآن",
                    ],
                },
                {
                    moavenat: "عدم معرفی تا دریافت نامه",
                    districts: [
                        "عدم معرفی تا دریافت نامه",
                    ],
                },
            ],
        };

        $(document).ready(function() {
            const selectState = document.getElementById("moavenat");
            const selectDistrict = document.getElementById("moarefi");
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
                        selectDistrict.append(createOption("Select moarefi", ""));
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
