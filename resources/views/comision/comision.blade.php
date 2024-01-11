@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-danger">لیست افراد کمیسیون</h3>
            <div class="row card-tools">

                <form action="">

                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" id="search" name="search" class="form-control float-right"
                            placeholder="جستجو" value="{{ request('search') }}">

                        <div class="input-group-append ">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">

            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>نام و نام خانوادگی</th>
                        <th>کدملی</th>
                        <th>محل خدمت</th>
                        <th>شماره پرونده</th>
                        <th class="text-center">حراست</th>
                        <th class="text-center">تولیت</th>
                        <th>خدام</th>
                        <th>سرمایه</th>
                        <th>عالی</th>
                        <th>تولیت</th>
                        <th class="text-center">اقدامات</th>
                    </tr>
                    @foreach ($list as $user)
                        <tr>
                            <td class="fontsBLotus">{{ $user->namesr }} - {{ $user->familysr }}</td>
                            <td class="fontsBLotus">{{ $user->codemsr }}</td>
                            <?php
                            $temp = \App\Khadem::find($user->id);
                            ?>
                            @foreach ($temp->comisions as $item)
                                @if (!$item->TnMahalKhsr)
                                    <td>
                                        نامشخص
                                    </td>
                                @else
                                    <td>
                                        {{ $item->TnMahalKhsr }}
                                    </td>
                                @endif
                                <td>
                                    {{ $item->documentId }}
                                </td>
                                <td>
                                    <ul class="list-group list-group-flush mr-2 text-center">
                                        <li class="list-group-item p-1">
                                            {{ $item->ShHerasatsr }}
                                        </li>
                                        <li class="list-group-item">
                                            @if ($item->TdHerasatsr == '1')
                                                <img src="/dist/img/checked.png" alt="">
                                            @else
                                                <img src="/dist/img/uncheck.png" alt="">
                                            @endif
                                        </li>
                                    </ul>

                                </td>
                                <td>
                                    <ul class="list-group list-group-flush mr-2 text-center">
                                        <li class="list-group-item p-1">
                                            {{ $item->ShToliatsr }}
                                        </li>
                                        <li class="list-group-item">
                                            @if ($item->TdToliatsr == '1')
                                                <img src="/dist/img/checked.png" alt="">
                                            @else
                                                <img src="/dist/img/uncheck.png" alt="">
                                            @endif
                                        </li>
                                </td>
                                <td>
                                    @if ($item->SiMKhodamsr == '1')
                                        <img src="/dist/img/checked.png" alt="">
                                    @else
                                        <img src="/dist/img/uncheck.png" alt="">
                                    @endif
                                </td>
                                <td>
                                    @if ($item->SiMSarmayehsr == '1')
                                        <img src="/dist/img/checked.png" alt="">
                                    @else
                                        <img src="/dist/img/uncheck.png" alt="">
                                    @endif
                                </td>
                                <td>
                                    @if ($item->SiMAalesr == '1')
                                        <img src="/dist/img/checked.png" alt="">
                                    @else
                                        <img src="/dist/img/uncheck.png" alt="">
                                    @endif
                                </td>
                                <td>
                                    @if ($item->SiToliatsr == '1')
                                        <img src="/dist/img/checked.png" alt="">
                                    @else
                                        <img src="/dist/img/uncheck.png" alt="">
                                    @endif
                                </td>
                            @endforeach

                            <td class="d-flex">

                                <!-- Trigger the modal with a button -->
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                    data-target=".myModal-{{ $user->id }}">
                                    اقدامات
                                </button>

                                <!-- Modal -->
                                <div class="modal fade mt-5 myModal-{{ $user->id }}" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form method="post" action='comision/{{ $user->id }}/sabt'>
                                                @csrf
                                                <div class="row d-flex">
                                                    <div class="col-6">
                                                        <div class="mt-3 d-flex">
                                                            <label for="documentId" name="documentId"
                                                                class="col-form-label mr-4">شماره پرونده:</label>
                                                            <input type="text" class="form-control w-50 m-auto" readonly
                                                                name="documentId" id="documentId"
                                                                value="{{ old('name', $item->documentId) }}">
                                                        </div>
                                                        <div class="mt-3 d-flex">
                                                            <label for="message-text" name="TnMahalKhsr"
                                                                class="col-form-label mr-4">محل خدمت:</label>
                                                            <select class="form-control w-50 mr-3 " id="type"
                                                                name="TnMahalKhsr">
                                                                <option value="دربان"
                                                                    {{ $item->TnMahalKhsr == 'دربان' ? 'selected' : '' }}>
                                                                    دربان</option>
                                                                <option value="کفشدار"
                                                                    {{ $item->TnMahalKhsr == 'کفشدار' ? 'selected' : '' }}>
                                                                    کفشدار</option>
                                                                <option value="فراش"
                                                                    {{ $item->TnMahalKhsr == 'فراش' ? 'selected' : '' }}>
                                                                    فراش</option>
                                                                <option value="خادم"
                                                                    {{ $item->TnMahalKhsr == 'خادم' ? 'selected' : '' }}>
                                                                    خادم</option>
                                                                <option value="حافظ"
                                                                    {{ $item->TnMahalKhsr == 'حافظ' ? 'selected' : '' }}>
                                                                    حافظ</option>
                                                            </select>
                                                        </div>

                                                        <div class="mt-3 d-flex">
                                                            <label for="ShHerasatsr" name="ShHerasatsr"
                                                                class="col-form-label mr-4">نامه حراست:</label>
                                                            <input type="text" class="form-control w-50 m-auto"
                                                                name="ShHerasatsr" id="ShHerasatsr"
                                                                value="{{ old('name', $item->ShHerasatsr) }}">
                                                        </div>
                                                        <div>
                                                            <label for="message-text" name="TdHerasatsr"
                                                                class="col-form-label m-4">تائید حراست:</label>
                                                            <input type="checkbox" id="TdHerasatsr" name="TdHerasatsr"
                                                                data-width="100" data-toggle="switchbutton"
                                                                {{ $item->TdHerasatsr == 1 ? 'checked' : '' }}
                                                                data-onstyle="info" data-offstyle="light"
                                                                data-onlabel="تایید" data-offlabel="عدم تایید">
                                                        </div>

                                                        <div class="mt-3 d-flex">
                                                            <label for="ShToliatsr" name="ShToliatsr"
                                                                class="col-form-label mr-4">نامه تولیت:</label>
                                                            <input type="text" class="form-control w-50 m-auto"
                                                                name="ShToliatsr" id="ShToliatsr"
                                                                value="{{ old('name', $item->ShToliatsr) }}">
                                                        </div>

                                                        <div>
                                                            <label for="message-text" name="TdToliatsr"
                                                                class="col-form-label m-4">تائید تولیت:</label>
                                                            <input type="checkbox" id="TdToliatsr" name="TdToliatsr"
                                                                data-width="100" data-toggle="switchbutton"
                                                                {{ $item->TdToliatsr == 1 ? 'checked' : '' }}
                                                                data-onstyle="info" data-offstyle="light"
                                                                data-onlabel="تایید" data-offlabel="عدم تایید">
                                                        </div>

                                                    </div>
                                                    <div class="col-6">

                                                        <div>
                                                            <label for="message-text" name="SiMKhodamsr"
                                                                class="col-form-label m-4">مدیریت خدام:</label>
                                                            <input type="checkbox" id="SiMKhodamsr" name="SiMKhodamsr"
                                                                data-width="100" data-toggle="switchbutton"
                                                                {{ $item->SiMKhodamsr == 1 ? 'checked' : '' }}
                                                                data-onstyle="info" data-offstyle="light"
                                                                data-onlabel="تایید" data-offlabel="عدم تایید">
                                                        </div>

                                                        <div>
                                                            <label for="message-text" name="SiMSarmayehsr"
                                                                class="col-form-label m-4">سرمایه انسانی:</label>
                                                            <input type="checkbox" id="SiMSarmayehsr"
                                                                name="SiMSarmayehsr" data-width="100"
                                                                data-toggle="switchbutton"
                                                                {{ $item->SiMSarmayehsr == 1 ? 'checked' : '' }}
                                                                data-onstyle="info" data-offstyle="light"
                                                                data-onlabel="تایید" data-offlabel="عدم تایید">
                                                        </div>

                                                        <div>
                                                            <label for="message-text" name="SiMAalesr"
                                                                class="col-form-label m-4">مدیریت عالی:</label>
                                                            <input type="checkbox" id="SiMAalesr" name="SiMAalesr"
                                                                data-width="100" data-toggle="switchbutton"
                                                                {{ $item->SiMAalesr == 1 ? 'checked' : '' }}
                                                                data-onstyle="info" data-offstyle="light"
                                                                data-onlabel="تایید" data-offlabel="عدم تایید">
                                                        </div>

                                                        <div>
                                                            <label for="message-text" name="SiToliatsr"
                                                                class="col-form-label m-4">امضای تولیت:</label>
                                                            <input type="checkbox" id="SiToliatsr" name="SiToliatsr"
                                                                data-width="100" data-toggle="switchbutton"
                                                                {{ $item->SiToliatsr == 1 ? 'checked' : '' }}
                                                                data-onstyle="info" data-offstyle="light"
                                                                data-onlabel="تایید" data-offlabel="عدم تایید">
                                                        </div>

                                                        <div class="mt-3 d-flex">
                                                            <label for="message-text" name="ShHokmsr"
                                                                class="col-form-label mr-4">شماره حکم:</label>
                                                            <input type="text" class="form-control w-50 m-auto"
                                                                name="ShHokmsr" id="ShHokmsr"
                                                                value="{{ old('name', $item->ShHokmsr) }}">
                                                        </div>
                                                        {{-- <img onClick="window.print()" class="mt-3" src="/dist/img/print.png" alt=""> --}}
                                                    </div>

                                                    <div class="modal-footer m-3">
                                                        <button type="submit" class="btn btn-primary">
                                                            ثبت
                                                        </button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- print                    --}}

                                <div class="printable">
                                    <button type="button"
                                        class="btn btn-outline-secondary mr-2 sprint{{ $user->id }}">S</button>
                                </div>

                                <div class="printable">
                                    <button type="button"
                                        class="btn btn-outline-warning mr-2 hprint{{ $user->id }}">g</button>
                                </div>

                                <div class="printSection alarm-{{ $user->id }}" style="margin: 0px auto;">
                                    <div class="row">
                                        <div class="col-3 float-right">
                                        </div>
                                        <img class="col-9 rounded imagehead float-left" src="/dist/img/newarm.jpg"
                                            alt="">
                                    </div>


                                    <div class="printThis" style="padding: 0px 150px;">
                                        </br>
                                        <p class="text-center inthename fontsnastaligh">
                                            قال الرضا (علیه السلام) : احرصوا علی قضاء حوائج المؤمنین و إدخال السرور
                                            علیهم...
                                            </br>
                                            حریصانه به دنبال برآوردن حاجات مؤمنین و شاد کردن آنها باشید (بحار النوار، ج ۷۸
                                            ص
                                            ۳۴۷).
                                            </br>
                                            </br>
                                        </p>
                                        <p class="text-right fontsBTitrBold">
                                            جناب آقای {{ $user->namesr }} {{ $user->familysr }}
                                        </p>

                                        <p class="fontsBLotus text-justify">
                                            &nbsp&nbsp&nbspنظر به بیش از ده سال سابقه خدمت شما در حرم مطهر
                                            و با عنایت به ارج و قداست خدمت به آستان ملکوتی امام همام ابوالحسن علی بن موسی
                                            الرضا (علیه آلاف التحیه والثناء) به موجب این حکم به عنوان
                                            {{ $item->TnMahalKhsr }} تشرفی منصوب و
                                            مفتخر می‌شوید.
                                            </br>
                                            امید است با کسب افتخار خدمت خالصانه در این آستان مقدس و رعایت دقیق ضوابط و
                                            دستورالعمل ها، بویژه رهنمودهای امام راحل و منشور ابلاغی از سوی رهبر معظم انقلاب
                                            اسلامی، توفیقات بیش از پیش را در ادای تکالیف الهی تحصیل و از فیوضات معنوی این
                                            خدمت
                                            بهره مند شوید.
                                        </p>
                                        </br>
                                        <div class="text-left fontsBTitrBold sighning">
                                            تولیت آستان قدس رضوی
                                            </br>
                                            احمد مروی&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </div>
                                        </br>
                                        </br>
                                        </br>
                                        <div class="fontsBLotus copytexts">
                                            رونوشت:
                                            </br>مدير محترم عالي حرم مطهّر رضوي جهت اقدام لازم.
                                            </br>معاون محترم سرمايه انساني جهت اقدام لازم.
                                        </div>
                                        </br>
                                        </br>
                                        </br>
                                        </br>
                                        <img class="rounded mx-auto d-block imagefooter" src="/dist/img/numhom.jpg"
                                            alt="">
                                    </div>

                                    <div class="row">
                                        <div class="col-3 float-right">
                                        </div>
                                        <img class="col-9 rounded imagehead float-left" src="/dist/img/newarm.jpg"
                                            alt="">
                                    </div>

                                    <div class="printThis" style="padding: 0px 150px;">
                                        </br>
                                        <p class="text-center inthename fontsnastaligh">
                                            قال الرضا (علیه السلام) : احرصوا علی قضاء حوائج المؤمنین و إدخال السرور
                                            علیهم...
                                            </br>
                                            حریصانه به دنبال برآوردن حاجات مؤمنین و شاد کردن آنها باشید (بحار النوار، ج ۷۸
                                            ص
                                            ۳۴۷).
                                            </br>
                                        </p>
                                        </br>
                                        <p class="text-right fontsBTitrBold">
                                            جناب آقای {{ $user->namesr }} {{ $user->familysr }}
                                        </p>

                                        <p class="fontsBLotus text-justify">
                                            &nbsp&nbsp&nbspنظر به بیش از ده سال سابقه خدمت شما در حرم مطهر
                                            و با عنایت به ارج و قداست خدمت به آستان ملکوتی امام همام ابوالحسن علی بن موسی
                                            الرضا (علیه آلاف التحیه والثناء) به موجب این حکم به عنوان
                                            {{ $item->TnMahalKhsr }} تشرفی منصوب و
                                            مفتخر می‌شوید.
                                            </br>
                                            امید است با کسب افتخار خدمت خالصانه در این آستان مقدس و رعایت دقیق ضوابط و
                                            دستورالعمل ها، بویژه رهنمودهای امام راحل و منشور ابلاغی از سوی رهبر معظم انقلاب
                                            اسلامی، توفیقات بیش از پیش را در ادای تکالیف الهی تحصیل و از فیوضات معنوی این
                                            خدمت
                                            بهره مند شوید.
                                        </p>
                                        </br>
                                        <div class="text-left fontsBTitrBold sighning">

                                            تولیت آستان قدس رضوی
                                            </br>
                                            احمد مروی&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                                        </div>
                                        <div class="fontsBLotus copytexts">
                                            رونوشت:
                                            </br>مدير محترم عالي حرم مطهّر رضوي جهت اقدام لازم.
                                            </br>معاون محترم سرمايه انساني جهت اقدام لازم.

                                        </div>
                                        </br>
                                        <div class="text-left fontsBTitrBold sighnings" style="font-size: 16px;">
                                            مدیریت عالی حرم مطهر رضوی
                                            </br>
                                            </br>
                                            معاون سرمایه انسانی&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                            </br>
                                            </br>
                                            مدیر امور خدام&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </div>
                                        </br>
                                        <img class="rounded mx-auto d-block imagefooter" src="/dist/img/numhom.jpg"
                                            alt="">
                                    </div>
                                </div>
                                {{--  --}}
                                <div class="printSection popuph-{{ $user->id }}" style="margin: 0px auto;">
                                    <div class="text-center">
                                        بسم الله الرحمن الرحیم
                                    </div>


                                    <div class="printThis" style="padding: 0px 150px;">
                                        </br>
                                        <p class="text-center inthename fontsnastaligh">
                                            قال الرضا (علیه السلام) : احرصوا علی قضاء حوائج المؤمنین و إدخال السرور
                                            علیهم...
                                            </br>
                                            حریصانه به دنبال برآوردن حاجات مؤمنین و شاد کردن آنها باشید (بحار النوار، ج ۷۸
                                            ص
                                            ۳۴۷).
                                            </br>
                                            </br>
                                        </p>
                                        <p class="text-center fontsnastaligh">
                                            جناب آقای {{ $user->namesr }} {{ $user->familysr }}
                                        </p>

                                        <p class="fontsnastaligh">
                                            &nbsp&nbsp&nbspنظر به بیش از ده سال سابقه خدمت شما در حرم مطهر
                                            و با عنایت به ارج و قداست خدمت به آستان ملکوتی امام همام ابوالحسن علی بن موسی
                                            الرضا (علیه آلاف التحیه والثناء) به موجب این حکم به عنوان
                                            {{ $item->TnMahalKhsr }} تشرفی منصوب و
                                            مفتخر می‌شوید.
                                            </br>
                                            امید است با کسب افتخار خدمت خالصانه در این آستان مقدس و رعایت دقیق ضوابط و
                                            دستورالعمل ها، بویژه رهنمودهای امام راحل و منشور ابلاغی از سوی رهبر معظم انقلاب
                                            اسلامی، توفیقات بیش از پیش را در ادای تکالیف الهی تحصیل و از فیوضات معنوی این
                                            خدمت
                                            بهره مند شوید.
                                        </p>
                                        </br>
                                        <div class="text-left fontsBTitrBold sighning">
                                            تولیت آستان قدس رضوی
                                            </br>
                                            احمد مروی&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </div>
                                        </br>
                                        </br>
                                        <div class="fontsBLotus copytexts">
                                            رونوشت:
                                            </br>مدير محترم عالي حرم مطهّر رضوي جهت اقدام لازم.
                                            </br>معاون محترم سرمايه انساني جهت اقدام لازم.
                                        </div>
                                        <img class="rounded mx-auto d-block imagefooter" src="/dist/img/numhom.jpg"
                                            alt="">
                                    </div>


                                </div>

        </div>
        <script>
            $(function() {
                $('.sprint{{ $user->id }}').on('click', function() {
                    $(".alarm-{{ $user->id }}").css('display', 'block');
                    $.print(".alarm-{{ $user->id }}");
                    $(".alarm-{{ $user->id }}").css('display', 'none');
                });
            });

            $(function() {
                $('.hprint{{ $user->id }}').on('click', function() {
                    $(".popuph-{{ $user->id }}").css('display', 'block');
                    $.print(".popuph-{{ $user->id }}");
                    $(".popuph-{{ $user->id }}").css('display', 'none');
                });
            });
        </script>
        {{-- endPrint --}}

        <a class="btn btn-sm btn-outline-danger mr-2 p-2" data-toggle="modal"
            data-target=".myModal-{{ $user->user_id }}">بایگانی</a>

        <!-- Modal -->
        <div class="modal fade mt-5 myModal-{{ $user->user_id }}" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="post" action='{{ url('/person/edit', $user->id) }}'>
                        @csrf
                        <div class="mb-3">

                            <p class="m-3">آیا از بایگانی فرد مطمئن هستید</p>
                            <input type="hidden" class="form-control w-50" name="bayegan" id="bayegan"
                                value="2">
                            <select class="form-control w-25 mr-4" id="dalil" name="dalil">
                                <option value=""> </option>
                                <option value="اتمام مراحل">اتمام فرآیند</option>
                                <option value="ابقاء">ابقاء</option>
                                <option value="انصراف">انصراف</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">بله</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>

        </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    <!-- /.card-body -->

    </div>
    <!-- /.card -->

    </div>
@endsection
