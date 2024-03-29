@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-danger">شرکت کنندگان در آزمون</h3>

            <div class="row card-tools">

                <form action="">

                    <div class="input-group input-group-sm" style="width: 200px;">
                        <a href="/azmoon" class="btn btn-default btn-default-sm ml-2">
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
                        <th>نام</th>
                        <th style="width: 12%;">نام خانوادگی</th>
                        <th>کدملی</th>
                        <th style="width: 12%;">محل خدمت</th>
                        <th>سنوات آزمون</th>
                        <th>شماره همراه</th>
                        <th style="width: 250px;">آزمون</th>
                        <th>اقدامات</th>
                    </tr>
                    @foreach ($khadem as $user)
                        <tr>
                            <td>
                                {{ $user->namesr }}
                            </td>
                            <td>{{ $user->familysr }}</td>
                            <td class="fontsBLotus">{{ $user->codemsr }}</td>
                            <td>{{ $user->bkhademyarsr }}</td>
                            <td class="fontsBLotus">{{ $user->marhalesr }}</td>
                            <td class="fontsBLotus">{{ $user->mobilesr }}</td>
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

                            <td class="d-flex">
                                <!-- Trigger the modal with a button -->

                                {{-- امتیاز بیشتر مساوی 70 --}}

                                @if ($item->nomrehAzmoonsr >= 70 && ($khadem->ShDarComision = 0))
                                    <form method="post" action="comision/{{ $user->id }}">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" class="form-control w-25 m-auto" name="ShDarComision"
                                            id="ShDarComision" value="1">
                                        <input type="hidden" class="form-control w-25 m-auto" name="dalil" id="dalil"
                                            value="قبولی در آزمون">
                                        <button class="btn btn-sm btn-warning mr-2">انتقال به کمیسیون</button>
                                    </form>
                                @elseif($item->nomrehAzmoonsr < 70 && $user->marhalesr == 3)
                                    <a class="btn btn-danger mr-2" data-toggle="modal"
                                        data-target=".myModal-{{ $user->id }}">بایگانی</a>

                                    <!-- Modal -->
                                    <div class="modal fade mt-5 myModal-{{ $user->id }}" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <form method="post" action='{{ url('/person/edit', $user->id) }}'>
                                                    @csrf
                                                    <div class="mb-3">

                                                        <p class="m-3">آیا از بایگانی فرد مطمئن هستید</p>
                                                        <input type="hidden" class="form-control w-50" name="bayegan"
                                                            id="bayegan" value="1">
                                                        <select class="form-control" id="dalil" name="dalil">
                                                            <option value="عدم کسب نمره لازم">عدم کسب نمره لازم</option>
                                                            <option value="عدم کسب نمره لازم">انصراف</option>
                                                            <option value="عدم کسب نمره لازم">ابقاء</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">بله</button>
                                                    </div>
                                                </form>

                                            </div>

                                        </div>
                                    </div>

                                    {{-- امتیاز کمتر از 70 یا بدون نمره --}}
                                @elseif (($item->nomrehAzmoonsr < 70) | !$item->nomrehAzmoonsr)

                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target=".myModal-{{ $user->id }}">
                                        ثبت نمره
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade mt-5 myModal-{{ $user->id }}" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <form method="post" action='azmoon/{{ $user->id }}/sabt'>
                                                    @csrf
                                                    <div class="mb-3 d-flex">
                                                        <label for="message-text" name="nomrehAz"
                                                            class="col-form-label mr-4">ثبت نمره آزمون:</label>
                                                        <input type="text" class="form-control w-25 m-auto nomreAz"
                                                            name="nomrehAz" id="nomrehAz" value="{{ $item->nomrehAzmoonsr }}">
                                                    </div>
                                                    <div class="mb-3 d-flex">
                                                        <label for="message-text" name="nomrehAz"
                                                            class="col-form-label mr-4">ثبت شغل:</label>
                                                        <input type="text" class="form-control w-25 m-auto"
                                                            name="job" value="{{ $item->job }}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            ثبت نمره
                                                        </button>
                                                    </div>
                                                </form>

                                            </div>

                                        </div>
                                    </div>
                                    @can('bayegani')
                                        <a class="btn btn-sm btn-danger mr-2" data-toggle="modal"
                                            data-target=".myModal-{{ $user->user_id }}">بایگانی</a>

                                        <!-- Modal -->
                                        <div class="modal fade mt-5 myModal-{{ $user->user_id }}" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post" action='{{ url('/person/edit', $user->id) }}'>
                                                        @csrf
                                                        <div class="mb-3">

                                                            <p class="m-3">آیا از بایگانی فرد مطمئن هستید</p>
                                                            <input type="hidden" class="form-control w-50" name="bayegan"
                                                                id="bayegan" value="1">
                                                            <select class="form-control" id="dalil" name="dalil">
                                                                <option value="عدم کسب نمره لازم">قبولی در آزمون</option>
                                                                <option value="عدم کسب نمره لازم">عدم کسب نمره لازم</option>
                                                                <option value="انصراف">انصراف</option>
                                                                <option value="ابقاء">ابقاء</option>
                                                            </select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">بله</button>
                                                        </div>
                                                    </form>

                                                </div>

                                            </div>
                                        </div>
                                    @endcan
                                @endif
                                @can('deport')
                                    <form action="delete/{{ $user->id }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger mr-2" type="submit">
                                            حذف
                                        </button>
                                    </form>
                                @endcan
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
