@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-danger">بخش بایگانی ارتقاء</h3>

            <div class="row card-tools">

                <form action="">

                    <div class="input-group input-group-sm ml-3" style="width: 200px;">
                        <a href="/bayegani" class="btn btn-default btn-default-sm ml-2">
                            <i class="fa fa-refresh" area-hidden= "true"></i>
                        </a>
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
                        <th>سنوات آزمون</th>
                        <th style="width: 250px;">آزمون</th>
                        <th>دلیل عدم قبولی</th>
                        <th>اقدامات</th>
                    </tr>
                    @foreach ($list as $user)
                        <tr>
                            <td>{{ $user->namesr }} - {{ $user->familysr }}</td>
                            <td>{{ $user->codemsr }}</td>
                            <td>{{ $user->bkhademyarsr }}</td>
                            <td>{{ $user->marhalesr }}</td>
                            <?php
                            $temp = \App\Khadem::find($user->id);
                            ?>

                            @foreach ($khadem = $temp->azmoons as $item)
                                @if ($item->nomrehAzmoonsr == 0)
                                    <td><button type="button" class="badge badge-info mt-2">عدم شرکت</button></td>
                                @elseif ($item->nomrehAzmoonsr >= 70)
                                    <td><button type="button" class="badge badge-success mt-2">قبول شده</button></td>
                                @elseif ($item->nomrehAzmoonsr < 70)
                                    <td><button type="button" class="badge badge-danger mt-2">عدم قبولی</button></td>
                                @endif
                            @endforeach
                            </td>
                            <td>
                                اتمام مراحل
                            </td>
                            <td class="d-flex">
                                <!-- Trigger the modal with a button -->

                                {{-- امتیاز بیشتر مساوی 70 --}}


                                <form action="bayegan/delete/{{ $user->id }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-danger mr-2" type="submit">
                                        حذف
                                    </button>
                                </form>
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
    <script>
        $('.modal').on('shown.bs.modal', function() {
            $('.nomreAz').focus();
        });
    </script>
@endsection
