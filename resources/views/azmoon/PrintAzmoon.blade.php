@extends('welcome')

@section('mohtava')
    <style>
        @font-face {
            font-family: "IsranNastaliq";
            src: url("dist/fonts/Vazir.ttf");
        }
    </style>
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title text-danger">لیست کلی افراد دعوت شده به شورای جذب، جهت ارتقاءبه خدمه تشرفی</h3>

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

        </div> --}}
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" id="printTable" style="direction: rtl;">
            <div class="text-center p-2 titles">لیست کلی افراد دعوت شده به شورای جذب، جهت ارتقاء به خدمه تشرفی - کمیسیون
                شماره 12 - (1402/06/27)</div>
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th style="width: 20%;">محل خدمت</th>
                        <th style="width: 20%;">نام و نام خانوادگی</th>
                        <th>شروع خدمت</th>
                        <th>تاریخ تولد</th>
                        <th style="width: 11%;">مدرک تحصیلی</th>
                        <th>انضباط</th>
                        <th>کیفی</th>
                        <th>شغل</th>
                        <th>ایثارگری</th>
                        <th>امتیاز</th>
                        <th style="width: 20%;">نتیجه مصاحبه</th>
                    </tr>
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'اماکن')
                            <tr>
                                <td>اماکن - {{ $user->bkhademyarsr }}</td>
                                <td>{{ $user->namesr }} - {{ $user->familysr }}</td>
                                <td>{{ $user->dateshsr }}</td>
                                <td style="font-family:iransance">{{ $user->tdatesr }}</td>
                                <td>{{ $user->madraksr }}</td>
                                <td>{{ $user->enzebatsr }}</td>
                                <td>{{ $user->keifisr }}</td>
                                @foreach ($khadems = $user->azmoons as $item)
                                    <td>{{ $item->job }}</td>
                                @endforeach
                                <td>{{ $user->isarsr }}</td>
                                <td>{{ $user->tajmi }}</td>
                                <td class="d-flex"></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'تبلیغات')
                            <tr>
                                <td>تبلیغات - {{ $user->bkhademyarsr }}</td>
                                <td>{{ $user->namesr }} - {{ $user->familysr }}</td>
                                <td>{{ $user->dateshsr }}</td>
                                <td>{{ $user->tdatesr }}</td>
                                <td>{{ $user->madraksr }}</td>
                                <td>{{ $user->enzebatsr }}</td>
                                <td>{{ $user->keifisr }}</td>
                                @foreach ($khadems = $user->azmoons as $item)
                                    <td>{{ $item->job }}</td>
                                @endforeach
                                <td>{{ $user->isarsr }}</td>
                                <td>{{ $user->tajmi }}</td>
                                <td class="d-flex"></td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach ($khadem as $user)
                        @if ($user->moavenat === 'امنیت')
                            <tr>
                                <td>امنیت - {{ $user->bkhademyarsr }}</td>
                                <td>{{ $user->namesr }} - {{ $user->familysr }}</td>
                                <td>{{ $user->dateshsr }}</td>
                                <td>{{ $user->tdatesr }}</td>
                                <td>{{ $user->madraksr }}</td>
                                <td>{{ $user->enzebatsr }}</td>
                                <td>{{ $user->keifisr }}</td>
                                @foreach ($khadems = $user->azmoons as $item)
                                    <td>{{ $item->job }}</td>
                                @endforeach
                                <td>{{ $user->isarsr }}</td>
                                <td>{{ $user->tajmi }}</td>
                                <td class="d-flex"></td>
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
                '@font-face { font-family: "IsranNastaliq" src: url("dist/fonts/Vazir.ttf")}' +
                '.titles, table th, table td {' +
                'border: 1px solid #ccc;' +
                'padding: 0.2em;' +
                'font-family: IsranNastaliq;' +
                'text-align: center;' +
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
