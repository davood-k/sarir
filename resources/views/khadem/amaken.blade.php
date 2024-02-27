@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">لیست اماکن متبرکه</h3>

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
        <div class="row table-responsive p-0">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>نام و نام خانوادگی</th>
                        <th>کدملی</th>
                        <th>محل خدمت</th>
                        <th>تاریخ شروع</th>
                        <th>شماره همراه</th>
                        <th>اقدامات</th>
                    </tr>
                    @foreach ($amaken as $user)
                        <tr>

                            <td>
                                @can('edit-user')
                                    <a class="btn btn-sm ml-2" href="{{ url('/person/show', $user->id) }}">
                                        <i class="fa fa-edit fa-info-square"></i>
                                    </a>
                                @endcan
                                {{ $user->namesr }} - {{ $user->familysr }}
                            </td>
                            <td>{{ $user->codemsr }}</td>
                            <td>{{ $user->bkhademyarsr }}</td>
                            <td>{{ $user->dateshsr }}</td>
                            <td>{{ $user->mobilesr }}</td>
                            <td class="d-flex">

                                <a class="btn btn-sm btn-info ml-2" href="{{ url('/person/show', $user->id) }}">مشاهده
                                    جزئیات</a>
                                {{-- <form action="delete/{{$user->id}}" method="post">
                            @csrf
                            @method('DELETE')
                           <button class="btn btn-sm btn-danger ml-2" type="submit">
                               حذف
                           </button>
                       </form>  --}}
                                <form method="post" action="azmoon/{{ $user->id }}">
                                    @csrf
                                    @method('put')
                                    <button class="btn btn-sm btn-primary">انتقال به آزمون</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $amaken->links() }}
        </div>
    </div>
    <!-- /.card -->
    </div>
@endsection
