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
                        <th>نامه حراست</th>
                        <th>حراست</th>
                        <th>نامه تولیت</th>
                        <th>تولیت</th>
                        <th>امور خدام</th>
                        <th>معاونت سرمایه</th>
                        <th>مدیریت عالی</th>
                        <th>تولیت</th>
                        <th>شماره حکم</th>
                        <th>اقدامات</th>
                    </tr>
                    @foreach ($list as $user)
                        <tr>
                            <td>{{ $user->namesr }}</td>
                            <td>{{ $user->familysr }}</td>
                            <td>{{ $user->codemsr }}</td>
                            <?php
                            $temp = \App\Khadem::find($user->id);
                            ?>

                            @foreach ($temp->comisions as $item)
                                <td>{{ $item->TnMahalKhsr }}</td>
                                <td>{{ $item->ShHerasatsr }}</td>
                                @if ($item->TdHerasatsr < 1)
                                    <td><button type="button" class="badge badge-danger mt-2">عدم تائید</button></td>
                                @elseif($item->TdHerasatsr = 1)
                                    <td><button type="button" class="badge badge-primary mt-2">تائید شده</button></td>
                                @endif
                                <td>{{ $item->ShToliatsr }}</td>
                                @if ($item->TdToliatsr < 1)
                                    <td><button type="button" class="badge badge-danger mt-2">عدم تائید</button></td>
                                @elseif($item->TdToliatsr = 1)
                                    <td><button type="button" class="badge badge-primary mt-2">تائید شده</button></td>
                                @endif
                                <td>
                                    {{ $item->SiMKhodamsr }}
                                </td>
                                <td>{{ $item->SiMSarmayehsr }}</td>
                                <td>{{ $item->SiMAalesr }}</td>
                                <td>{{ $item->SiToliatsr }}</td>
                                <td>{{ $item->ShHokmsr }}</td>
                            @endforeach

                            <td class="d-flex">

                                <a class="btn btn-sm btn-primary ml-2"
                                    href="{{ url('/person/show', $user->id) }}">بازگردانی به کمیسیون</a>

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
