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
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>کدملی</th>
                        <th>محل خدمت</th>
                        <th>شماره پرونده</th>

                        <th>خدام</th>
                        <th>سرمایه</th>
                        <th>عالی</th>
                        <th>تولیت</th>
                        <th class="text-center">اقدامات</th>
                    </tr>
                    @foreach ($list as $user)
                        <?php
                        $temp = \App\Khadem::find($user->id);
                        ?>

                        <tr>
                            <td class="fontsBLotussm">{{ $user->namesr }}</td>
                            <td class="fontsBLotussm">{{ $user->familysr }}</td>
                            <td class="fontsBLotussm">{{ $user->codemsr }}</td>

                            @foreach ($temp->comisions as $item)
                                @if (!$item->TnMahalKhsr)
                                    <td class="fontsBLotussm text-red">
                                        نامشخص
                                    </td>
                                @else
                                    <td class="fontsBLotussm">
                                        {{ $item->TnMahalKhsr }}
                                    </td>
                                @endif
                                <td class="fontsBLotussm">
                                    {{ $item->documentId }}
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
                                    <i class="fa fa-edit fa-edit-square"></i>
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
                                                            <input type="checkbox" id="SiMSarmayehsr" name="SiMSarmayehsr"
                                                                data-width="100" data-toggle="switchbutton"
                                                                {{ $item->SiMSarmayehsr == 1 ? 'checked' : '' }}
                                                                data-onstyle="info" data-offstyle="light"
                                                                data-onlabel="تایید" data-offlabel="عدم تایید">
                                                        </div>

                                                    </div>
                                                    <div class="col-6">

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

                                <div class="row printSection alarm-{{ $user->id }}" style="margin: 0px auto;">
                                    <div class="row">
                                        <div class="col-2 float-right">
                                        </div>
                                        <img class="col-10 rounded imagehead float-left ml-4" src="/dist/img/newarm.jpg"
                                            alt="">
                                    </div>


                                    <div class="printThis" style="padding: 0px 150px;margin-top: 200px;">
                                        </br>
                                        <p class="text-center inthename fontsnastaligh ml-4">
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
                                            احمد مروی&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
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
                                        <div class="col-2 float-right">
                                        </div>
                                        <img class="col-10 rounded imagehead float-left ml-4" src="/dist/img/newarm.jpg"
                                            alt="">
                                    </div>

                                    <div class="printThis" style="padding: 0px 150px;">
                                        </br>
                                        </br>
                                        </br>
                                        </br>
                                        </br>
                                        </br>
                                        </br>
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
                                            احمد مروی&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

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
                                        </br>
                                        <img class="rounded mx-auto d-block imagefooter" src="/dist/img/numhom.jpg"
                                            alt="">
                                    </div>
                                </div>
                                {{--  --}}
                                <div class="fontsnastaligh printSection popuph-{{ $user->id }}"
                                    style="margin: 0px auto;">
                                    <div class="row">
                                        <div class="col-12-md d-flex">

                                            <div class="col-4-md mx-auto">

                                            </div>
                                            <div class="col-4-md mt-5" style="font-size: 20px;margin-left:245px;">
                                                </br>
                                                شماره:
                                                </br>
                                                تاریخ:
                                            </div>

                                        </div>

                                    </div>



                                    <div class="printThis" style="padding: 0px 150px;">
                                        </br>
                                        </br>
                                        <p class="text-center inthename fontsnastaligh" style="font-size: 22px;">
                                            قال الرضا (علیه السلام) : احرصوا علی قضاء حوائج المؤمنین و إدخال السرور
                                            علیهم...
                                            </br>
                                            حریصانه به دنبال برآوردن حاجات مؤمنین و شاد کردن آنها باشید (بحار النوار، ج ۷۸
                                            ص
                                            ۳۴۷).
                                        </p>
                                        <p class="text-center fontsnastaligh" style="font-size: 42px;">
                                            جناب آقای {{ $user->namesr }} {{ $user->familysr }}
                                        </p>

                                        <p class="fontsnastaligh sighningses text-center">
                                            &nbsp&nbsp&nbspنظر به بیش از ده سال سابقه خدمت شما در حرم مطهر</br>
                                            و با عنایت به ارج و قداست خدمت به آستان ملکوتی امام همام</br>
                                            <img class="rounded m-auto d-block" src="/dist/img/logo.jpg" alt="">
                                            به موجب این حکم به عنوان
                                            {{ $item->TnMahalKhsr }}<b> تشرفی منصوب و</b>
                                            مفتخر می‌شوید.
                                            امید است با کسب افتخار</br> خدمت خالصانه در این آستان مقدس و رعایت دقیق ضوابط و
                                            دستورالعمل ها، بویژه رهنمودهای</br> امام راحل و منشور ابلاغی از سوی رهبر معظم
                                            انقلاب
                                            اسلامی، توفیقات بیش از پیش را در ادای</br> تکالیف الهی تحصیل و از فیوضات معنوی
                                            این خدمت بهره مند شوید.
                                        </p>
                                        <div class="text-center fontsnastaligh">
                                            تولیت آستان قدس رضوی
                                            </br>
                                            <div class="text-center fontsnastaligh">
                                                احمد مروی
                                            </div>
                                        </div>

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
        @can('bayegani')
            <a class="btn btn-sm btn-outline-danger mr-2 p-2" data-toggle="modal"
                data-target=".myModal-{{ $user->user_id }}">بایگانی</a>
        @endcan
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
