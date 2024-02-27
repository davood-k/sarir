@extends('welcome')

@section('mohtava')
    <style>
        @font-face {
            font-family: "BLotus";
            src: url("../fonts/BLotus.ttf");
        }
    </style>
    <div class="card">
        <div class="card-body table-responsive p-0" id="printTable" style="direction: rtl;">
            <div class="text-center p-2 titles">
                <h4>
                    لیست کلی افراد دعوت شده به شورای جذب، جهت ارتقاء به خدمه تشرفی - کمیسیون
                    شماره 16 - (1402/10/28)
                </h4>
            </div>
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th style="width: 12%;">محل خدمت</th>
                        <th style="width: 10%;">نام</th>
                        <th style="width: 10%;">نام خانوادگی</th>
                        <th>شروع خدمت</th>
                        <th>تاریخ تولد</th>
                        <th style="width: 7%;">مدرک تحصیلی</th>
                        <th style="width: 5%;">حضور و غیاب </th>
                        <th style="width: 5%;">جنبه های کیفی</th>
                        <th style="width: 9%;">شغل</th>
                        <th>سوابق ایثارگری</th>
                        <th>امتیاز</th>
                        <th style="width: 20%;">نتیجه مصاحبه</th>
                    </tr>
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'اماکن')
                            <tr>
                                <td class="fontsBLotussm">اماکن - {{ $user->bkhademyarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->namesr }}</td>
                                <td class="fontsBLotussm">{{ $user->familysr }}</td>
                                <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                                <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                                @foreach ($khadems = $user->azmoons as $item)
                                    <td class="fontsBLotussm">{{ $item->job }}</td>
                                @endforeach
                                <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tajmi }}</td>
                                <td class="d-flex fontsBLotussm"></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'تبلیغات')
                            <tr>
                                <td class="fontsBLotussm">تبلیغات - {{ $user->bkhademyarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->namesr }}</td>
                                <td class="fontsBLotussm">{{ $user->familysr }}</td>
                                <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                                <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                                @foreach ($khadems = $user->azmoons as $item)
                                    <td class="fontsBLotussm">{{ $item->job }}</td>
                                @endforeach
                                <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tajmi }}</td>
                                <td class="d-flex fontsBLotussm"></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'امنیت')
                            <tr>
                                <td>امنیت - {{ $user->bkhademyarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->namesr }}</td>
                                <td class="fontsBLotussm">{{ $user->familysr }}</td>
                                <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                                <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                                @foreach ($khadems = $user->azmoons as $item)
                                    <td class="fontsBLotussm">{{ $item->job }}</td>
                                @endforeach
                                <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tajmi }}</td>
                                <td class="d-flex fontsBLotussm"></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'همکاران')
                            <tr>
                                <td class="fontsBLotussm">کارمند</td>
                                <td class="fontsBLotussm">{{ $user->namesr }}</td>
                                <td class="fontsBLotussm">{{ $user->familysr }}</td>
                                <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                                <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                                @foreach ($khadems = $user->azmoons as $item)
                                    <td class="fontsBLotussm">{{ $item->job }}</td>
                                @endforeach
                                <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tajmi }}</td>
                                <td class="d-flex fontsBLotussm"></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->

    </div>
    <!-- /.card -->

    </div>
    <div class="row">
        <button class="col-1 btn btn-sm btn-primary m-3">پرینت</button>
    </div>
    <script>
        function printData() {

            var divToPrint = document.getElementById("printTable");

            var htmlToPrint = '' + '<style type="text/css">' +
                '.titles, table th, table td {' +
                'border: 1px solid #ccc;' +
                'padding: 0.1em;' +
                'font-family: B Lotus;' +
                'font-size: 14px;' +
                'text-align: center;' +
                '}' +
                '</style>';

            htmlToPrint += divToPrint.outerHTML;
            newWin = window.open("");
            newWin.document.write(
                htmlToPrint);
            newWin.print();
            newWin.close();
        }

        $('button').on('click', function() {
            printData();
        })
    </script>
@endsection
