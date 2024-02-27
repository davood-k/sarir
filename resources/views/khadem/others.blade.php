@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">لیست متفرقه</h3>

            <div class="row card-tools">

                <form action="">

                    <div class="input-group input-group-sm ml-3" style="width: 200px;">
                        <a href="/basij" class="btn btn-default btn-default-sm ml-2">
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
                        <th>سال شروع خدمت</th>
                        <th>اقدامات</th>
                    </tr>
                    @foreach ($elmi as $user)
                        <tr>
                            <td>
                                <a class="btn btn-sm ml-2" href="{{ url('/person/show', $user->id) }}">
                                    <i class="fa fa-edit fa-edit-square"></i>
                                </a>
                                {{ $user->namesr }} - {{ $user->familysr }}
                            </td>
                            <td>{{ $user->codemsr }}</td>
                            <td>{{ $user->bkhademyarsr }}</td>
                            <td>{{ $user->dateshsr }}</td>
                            <td class="d-flex">

                                <a class="btn btn-sm btn-info ml-2" href="{{ url('/person/show', $user->id) }}">مشاهده
                                    جزئیات</a>
                                {{-- <a class="btn btn-sm btn-warning ml-2" href="{{ url('/person/edit', $user->id) }}">ویرایش
                                    خادمیار</a> --}}
                                {{-- <form action="delete/{{$user->id}}" method="post">
                            @csrf
                            @method('DELETE')
                           <button class="btn btn-sm btn-danger ml-2" type="submit">
                               حذف
                           </button>
                       </form> --}}
                                @if ($user->sherkatDarAzsr == 0)
                                    <form method="post" action="azmoon/{{ $user->id }}">
                                        @csrf
                                        @method('put')
                                        <button class="btn btn-sm btn-info">انتقال به آزمون</button>
                                    </form>
                                @endif
                    @endforeach
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $elmi->links() }}
        </div>
    </div>
    <!-- /.card -->
    </div>
@endsection
