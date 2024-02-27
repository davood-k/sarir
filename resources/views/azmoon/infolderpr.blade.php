@extends('welcome')

@section('mohtava')
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
                        <th>کدخدمتی</th>
                        <th>محل خدمت</th>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>شروع خدمت</th>
                        <th>تاریخ تولد</th>
                        <th>مدرک تحصیلی</th>
                        <th>سنوات</th>
                        <th>حضور و غیاب</th>
                        <th>جنبه های کیفی</th>
                        <th>تحصیلات</th>
                        <th>سوابق ایثارگری</th>
                        <th>نخبگان، مهارت</th>
                        <th>امتیاز</th>
                    </tr>
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'اماکن')
                            <tr>
                                <td class="fontsBLotussm">{{ $user->codemsr }}</td>
                                <td>اماکن - {{ $user->bkhademyarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->namesr }}</td>
                                <td class="fontsBLotussm">{{ $user->familysr }}</td>
                                <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                                <td class="fontsBLotussm">{{ $user->sanvatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                                <td class="fontsBLotussm">{{ $user->tahsilsr }}</td>
                                <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->nokhbehsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tajmi }}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'تبلیغات')
                            <tr>
                                <td class="fontsBLotussm">{{ $user->codemsr }}</td>
                                <td>تبلیغات - {{ $user->bkhademyarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->namesr }}</td>
                                <td class="fontsBLotussm">{{ $user->familysr }}</td>
                                <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                                <td class="fontsBLotussm">{{ $user->sanvatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                                <td class="fontsBLotussm">{{ $user->tahsilsr }}</td>
                                <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->nokhbehsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tajmi }}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'امنیت')
                            <tr>
                                <td class="fontsBLotussm">{{ $user->codemsr }}</td>
                                <td>حفاظت بسیج</td>
                                <td class="fontsBLotussm">{{ $user->namesr }}</td>
                                <td class="fontsBLotussm">{{ $user->familysr }}</td>
                                <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                                <td class="fontsBLotussm">{{ $user->sanvatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                                <td class="fontsBLotussm">{{ $user->tahsilsr }}</td>
                                <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                                <td class="fontsBLotussm">{{ $user->nokhbehsr }}</td>
                                <td class="fontsBLotussm">{{ $user->tajmi }}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                    @if ($user->moavenat === 'همکاران')
                        <tr>
                            <td class="fontsBLotussm">{{ $user->codemsr }}</td>
                            <td>کارمند</td>
                            <td class="fontsBLotussm">{{ $user->namesr }}</td>
                            <td class="fontsBLotussm">{{ $user->familysr }}</td>
                            <td class="fontsBLotussm">{{ $user->dateshsr }}</td>
                            <td class="fontsBLotussm">{{ $user->tdatesr }}</td>
                            <td class="fontsBLotussm">{{ $user->madraksr }}</td>
                            <td class="fontsBLotussm">{{ $user->sanvatsr }}</td>
                            <td class="fontsBLotussm">{{ $user->enzebatsr }}</td>
                            <td class="fontsBLotussm">{{ $user->keifisr }}</td>
                            <td class="fontsBLotussm">{{ $user->tahsilsr }}</td>
                            <td class="fontsBLotussm">{{ $user->isarsr }}</td>
                            <td class="fontsBLotussm">{{ $user->nokhbehsr }}</td>
                            <td class="fontsBLotussm">{{ $user->tajmi }}</td>
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
                'padding: 0.15em;' +
                'font-size: 14px;' +
                'text-align: center;' +
                'font-family: B Lotus' +
                '}' +
                '</style>';
            htmlToPrint += divToPrint.outerHTML;
            newWin = window.open("");
            newWin.document.write(htmlToPrint);
            newWin.print();
            newWin.close();
        }

        $('button').on('click', function() {
            printData();
        })
    </script>
@endsection
