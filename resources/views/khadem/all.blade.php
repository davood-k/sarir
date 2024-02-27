@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">لیست کلی</h3>

            <div class="row card-tools">

                <form action="">

                    <div class="input-group input-group-sm ml-3" style="width: 150px;">
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
                        <th class="text-center">نام</th>
                        <th>نام خانوادگی</th>
                        <th>کدملی</th>
                        <th>محل خدمت</th>
                        <th>شروع خدمت</th>
                        <th>شماره همراه</th>
                        <th class="text-center">سطح</th>
                    </tr>
                    @foreach ($all as $user)
                        <tr>
                            <td>
                                <a class="btn btn-sm ml-2" href="{{ url('/person/show', $user->id) }}">
                                    <i class="fa fa-edit fa-edit-square"></i>
                                </a>
                                {{-- <a class="btn btn-sm btn-warning ml-2" href="{{ url('/person/edit', $user->id) }}">
                                    <i class="fa fa-pencil"></i>
                                </a> --}}
                                {{ $user->namesr }}
                            </td>
                            <td>
                                {{ $user->familysr }}
                            </td>
                            <td>
                                {{ $user->codemsr }}
                            </td>
                            <td>
                                {{ $user->bkhademyarsr }}
                            </td>
                            <td>
                                {{ $user->dateshsr }}
                            </td>
                            <td>
                                {{ $user->mobilesr }}
                            </td>
                            <td class="d-flex">

                                {{-- <form action="delete/{{$user->id}}" method="post">
                            @csrf
                            @method('DELETE')
                           <button class="btn btn-sm btn-danger ml-2" type="submit">
                               حذف
                           </button>
                       </form> --}}

                                @if ($user->bayeganisr == 2)
                                    <a href="/bayegani" style="border: 1px solid rgb(255, 14, 14);"
                                        class="btn btn-sm bg-secondery">بایگانی شده</a>
                                @elseif ($user->ShDarComision == 1)
                                    <a href="comision" style="border: 1px solid rgb(62, 170, 29);"
                                        class="btn btn-sm bg-secondery">مرحله
                                        کمیسیون </a>
                                @elseif ($user->sherkatDarAzsr == 2)
                                    <a href="/taeedeazmoon" style="border: 1px solid rgb(184, 106, 62);"
                                        class="btn btn-sm bg-secondery">مرحله
                                        تائید
                                        آزمون
                                    </a>
                                @elseif ( $user->sherkatDarAzsr == 3)
                                    <a href="/readyInvitation" style="border: 1px solid rgb(184, 106, 62);"
                                        class="btn btn-sm bg-secondery">مرحله
                                        مدعوین
                                    </a>
                                @elseif ($user->sherkatDarAzsr == 1)
                                    <a href="/azmoon" style="border: 1px solid rgb(74, 62, 184);"
                                        class="btn btn-sm bg-secondery">
                                        مرحله آزمون
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $all->links() }}
        </div>
    </div>
    <!-- /.card -->
    </div>
@endsection
