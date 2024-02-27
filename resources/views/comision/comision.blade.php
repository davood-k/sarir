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
                    <button class="btn btn-primary m-3 btnprint"><i class="fa fa-print fa-print-square"></i></button>

                    <tr>
                        <th style="width: 15%;">نام</th>
                        <th>نام خانوادگی</th>
                        <th>کدملی</th>
                        <th>محل خدمت</th>
                        <th>شماره پرونده</th>
                        <th>تاریخ مصاحبه</th>
                        <th class="text-center">حراست</th>
                        <th class="text-center">تائید حراست</th>
                        <th class="text-center">تولیت</th>
                        <th class="text-center">تائید تولیت</th>
                        <th class="text-center">اقدامات</th>
                    </tr>
                    @foreach ($list as $user)
                        <?php
                        $temp = \App\Khadem::find($user->id);
                        ?>

                        <tr>
                            <td class="fontsBLotussm">
                                <input class="ml-1 chk" type="checkbox" name="rowinfo" data-name="{{ $user->namesr }}"
                                    data-family="{{ $user->familysr }}"
                                    data-documentId="{{ $temp->comisions->first()->documentId }}"
                                    data-tnMahalKhsr ="{{ $temp->comisions->first()->TnMahalKhsr }}"
                                    onclick="changeSelected(this)">
                                {{ $user->namesr }}
                            </td>
                            <td class="fontsBLotussm">{{ $user->familysr }}</td>
                            <td class="fontsBLotussm">{{ $user->codemsr }}</td>
                            <td class="fontsBLotussm">{{ $user->id }}</td>

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
                                <td class="alarm-{{ $user->id }} fontsBTitr ">
                                    {{ $item->documentId }}
                                </td>
                                <td class="fontsBLotussm">
                                    {{ $item->dateInterview }}
                                </td>
                                <td class="fontsBLotussm">

                                    {{ $item->ShHerasatsr }}

                                </td>
                                <td class="fontsBLotussm">

                                    @if ($item->TdHerasatsr == '1')
                                        <img src="/dist/img/checked.png" alt="">
                                    @else
                                        <img src="/dist/img/uncheck.png" alt="">
                                    @endif

                                </td>

                                <td class="fontsBLotussm">

                                    {{ $item->ShToliatsr }}

                                </td>
                                <td class="fontsBLotussm">

                                    @if ($item->TdToliatsr == '1')
                                        <img src="/dist/img/checked.png" alt="">
                                    @else
                                        <img src="/dist/img/uncheck.png" alt="">
                                    @endif

                                </td>
                            @endforeach

                            <td class="d-flex">

                                {{-- مدال برای حکم ها --}}
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                    data-target=".myModal-{{ $user->id }}">
                                    <i class="fa fa-edit fa-edit-square"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade mt-5 myModal-{{ $user->id }}" role="dialog">
                                    <div class="modal-dialog">


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
                                                            <select class="form-control w-50 mr-3" id="type"
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
                                                                <option value="خادم علمی"
                                                                    {{ $item->TnMahalKhsr == 'خادم علمی' ? 'selected' : '' }}>
                                                                    خادم علمی</option>
                                                            </select>
                                                        </div>

                                                        <div class="mt-3 d-flex">
                                                            <label for="ShHerasatsr" name="ShHerasatsr"
                                                                class="col-form-label mr-4">نامه حراست:</label>
                                                            <input type="text" class="form-control w-50 m-auto"
                                                                name="ShHerasatsr" id="ShHerasatsr"
                                                                value="{{ old('name', $item->ShHerasatsr) }}">
                                                        </div>
                                                        <div class="mt-3 d-flex">
                                                            <label for="dateInterview" name="dateInterview"
                                                                class="col-form-label mr-4">تاریخ مصاحبه:</label>
                                                            <input type="text" class="form-control w-50 m-auto"
                                                                name="dateInterview" id="dateInterview"
                                                                value="{{ old('name', $item->dateInterview) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
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
                                {{-- پایان مدال برای حکم ها --}}

                                {{-- مدال بایگانی --}}
                                @can('bayegani')
                                    <a class="btn btn-sm btn-outline-danger mr-2 p-2" data-toggle="modal"
                                        data-target=".sendIssuance-{{ $user->id }}">ارسال به احکام</a>
                                    <div class="modal fade mt-5 sendIssuance-{{ $user->id }}" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <form method="post" action='{{ url('/sendperson/edit', $user->id) }}'>
                                                    @csrf
                                                    <div class="mb-3">

                                                        <p class="m-3">آیا از انتقال نامبرده به لیست احکام مطمئن هستید؟</p>
                                                        <input type="hidden" class="form-control w-50" name="Issuance"
                                                            id="Issuance" value="2">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">بله</button>
                                                    </div>
                                                </form>

                                            </div>

                                        </div>

                                    </div>
                                @endcan
                                {{-- پایان مدال بایگانی --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="print-data" style="direction: rtl;display:none;">
    </div>

    <script>
        function changeSelected(chk) {
            $('.chk:checked').each(function(index, obj) {
                let name = $(obj).attr('data-name');
                let family = $(obj).attr('data-family');
                let documentId = $(obj).attr('data-documentId');
                let tnMahalKhsr = $(obj).attr('data-tnMahalKhsr');
                addPrintRow(index == 0 ? true : false, name, family, documentId, tnMahalKhsr);
            });
        }

        function addPrintRow(setEmpty, name, family, documentId, tnMahalKhsr) {
            if (setEmpty == true) {
                $('#print-data').empty();
            }

            let row =
                '<div style="text-align:center;display:block;float:right;margin-top: 20px;"><div style="border-radius:5px;float:right;width: 400px;border:2px solid black;font-size: 44px;font-weight: bold;padding:26px;">' +
                name + ' ' + family +
                '</div><div style="float:left;margin-right: 20px;width: 150px;border-radius:5px;border:2px solid black;"><div style="font-size: 35px;font-weight: bold;float: center;padding:3px;">' +
                documentId + '</br>' + tnMahalKhsr +
                '</div></div><br />';
            $('#print-data').append(row);

        };

        function printData() {

            var divToPrint = document.getElementById("print-data");

            var htmlToPrint = '' + '<style type="text/css">' +
                '* {' +
                'font-family: B Titr;' +
                'border-radus; 1px black solid' +
                '}' +
                '</style>';

            htmlToPrint += divToPrint.outerHTML;
            newWin = window.open("");
            newWin.document.write(
                htmlToPrint);
            newWin.print();
            newWin.close();
        }

        $('.btnprint').on('click', function() {
            $("#print-data").css('display', 'block');
            printData();
            $("#print-data").css('display', 'none');
        })
    </script>
@endsection
