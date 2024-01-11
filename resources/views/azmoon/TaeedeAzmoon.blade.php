@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-danger">لیست کلی افراد شرکت کننده درآزمون</h3>

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
                        <th>سنوات آزمون</th>
                        <th>آزمون</th>
                        <th>اقدامات</th>
                    </tr>
                    @foreach ($khadem as $user)
                        <tr>
                            <td>{{ $user->namesr }}</td>
                            <td>{{ $user->familysr }}</td>
                            <td>{{ $user->codemsr }}</td>
                            <td>{{ $user->bkhademyarsr }}</td>
                            <td>{{ $user->mobilesr }}</td>
                            <?php
                            $temp = \App\Khadem::find($user->id);
                            ?>
                            @foreach ($khadem = $temp->azmoons as $item)
                                    <td><button type="button" class="btn btn-sm btn-info mt-2">{{ $item->nomrehAzmoonsr }}</button></td>
                            @endforeach
                            
                            <td class="d-flex">

                                <form method="post" action="comision/{{ $user->id }}">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" class="form-control w-25 m-auto" name="ShDarComision"
                                        id="ShDarComision" value="1">
                                    <input type="hidden" class="form-control w-25 m-auto" name="dalil" id="dalil"
                                        value="قبولی در آزمون">
                                    <button class="btn btn-sm btn-warning mr-2">انتقال به کمیسیون</button>
                                </form>
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
    <script></script>
@endsection
