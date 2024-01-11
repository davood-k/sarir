@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <div class="row card-tools">
                {{-- <form action="">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" id="search" name="search" class="form-control float-right" placeholder="جستجو"
                            value="{{ request('search') }}">

                        <div class="input-group-append ">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form> --}}
            </div>

        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" id="printTable" style="direction: rtl;">
            <div class="text-center p-2 titles">لیست کلی افراد دعوت شده به شورای جذب، جهت ارتقاء به خدمه تشرفی - کمیسیون
                شماره 12 - (1402/06/27)</div>
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>کدخدمتی</th>
                        <th>محل خدمت</th>
                        <th>نام و نام خانوادگی</th>
                        <th>شروع خدمت</th>
                        <th>تاریخ تولد</th>
                        <th>مدرک تحصیلی</th>
                        <th>سنوات</th>
                        <th>انضباط</th>
                        <th>کیفی</th>
                        <th>مدرک</th>
                        <th>ایثارگری</th>
                        <th>نخبگان، مهارت</th>
                        <th>امتیاز</th>
                    </tr>
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'اماکن')
                            <tr>
                                <td class="fontsBLotus">{{ $user->codemsr }}</td>
                                <td>اماکن - {{ $user->bkhademyarsr }}</td>
                                <td>{{ $user->namesr }} - {{ $user->familysr }}</td>
                                <td class="fontsBLotus">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotus">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotus">{{ $user->madraksr }}</td>
                                <td class="fontsBLotus">{{ $user->sanvatsr }}</td>
                                <td class="fontsBLotus">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotus">{{ $user->keifisr }}</td>
                                <td class="fontsBLotus">{{ $user->tahsilsr }}</td>
                                <td class="fontsBLotus">{{ $user->isarsr }}</td>
                                <td class="fontsBLotus">{{ $user->nokhbehsr }}</td>
                                <td class="fontsBLotus">{{ $user->tajmi }}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'تبلیغات')
                            <tr>
                                <td class="fontsBLotus">{{ $user->codemsr }}</td>
                                <td>تبلیغات - {{ $user->bkhademyarsr }}</td>
                                <td>{{ $user->namesr }} - {{ $user->familysr }}</td>
                                <td class="fontsBLotus">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotus">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotus">{{ $user->madraksr }}</td>
                                <td class="fontsBLotus">{{ $user->sanvatsr }}</td>
                                <td class="fontsBLotus">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotus">{{ $user->keifisr }}</td>
                                <td class="fontsBLotus">{{ $user->tahsilsr }}</td>
                                <td class="fontsBLotus">{{ $user->isarsr }}</td>
                                <td class="fontsBLotus">{{ $user->nokhbehsr }}</td>
                                <td class="fontsBLotus">{{ $user->tajmi }}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'امنیت')
                            <tr>
                                <td class="fontsBLotus">{{ $user->codemsr }}</td>
                                <td>امنیت - {{ $user->bkhademyarsr }}</td>
                                <td>{{ $user->namesr }} - {{ $user->familysr }}</td>
                                <td class="fontsBLotus">{{ $user->dateshsr }}</td>
                                <td class="fontsBLotus">{{ $user->tdatesr }}</td>
                                <td class="fontsBLotus">{{ $user->madraksr }}</td>
                                <td class="fontsBLotus">{{ $user->sanvatsr }}</td>
                                <td class="fontsBLotus">{{ $user->enzebatsr }}</td>
                                <td class="fontsBLotus">{{ $user->keifisr }}</td>
                                <td class="fontsBLotus">{{ $user->tahsilsr }}</td>
                                <td class="fontsBLotus">{{ $user->isarsr }}</td>
                                <td class="fontsBLotus">{{ $user->nokhbehsr }}</td>
                                <td class="fontsBLotus">{{ $user->tajmi }}</td>
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
                'padding: 0.2em;' +
                'text-align: center;' +
                'font-family: BLotus' +
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
