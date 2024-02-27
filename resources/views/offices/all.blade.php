@extends('welcome')

@section('mohtava')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات بخش ها</h3>

                    <div class="card-tools d-flex">
                        <form action="">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو"
                                    value="{{ request('search') }}">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        @can('add-offices')
                            <div class="btn-group-sm mr-1">
                                <a href="{{ route('informationOffice.create') }}" class="btn btn-info">ایجاد بخش جدید</a>
                            </div>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" style="background-color: bisque;">
                        <tbody>
                            <tr>
                                <th>نام بخش</th>
                                <th>نام رابط</th>
                                <th>داخلی</th>
                                {{-- <th>همراه</th> --}}
                                <th>آدرس</th>
                                <th>نوع کشیک</th>
                                <th>ساعت خدمتی</th>
                                <th>توضیحات</th>
                                <th>اقدامات</th>
                            </tr>

                            @foreach ($list as $user)
                                <tr>
                                    <td>{{ $user->offices }}</td>
                                    <td>{{ $user->personsRelation }}</td>
                                    <td>{{ $user->numbers }}</td>
                                    {{-- <td>{{ $user->mobiles }}</td> --}}
                                    <td>{{ $user->address }}</td>
                                    <td>
                                        @if ($user->post == '2')
                                            چرخشی
                                        @else
                                            ثابت
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->timeServices == '1')
                                            6 ساعته
                                        @elseif ($user->timeServices == '2')
                                            8 ساعته
                                        @else
                                            12 ساعته
                                        @endif
                                    </td>
                                    <td>{{ $user->description }}</td>
                                    <td class="d-flex">
                                        @can('add-offices')
                                            <a href="{{ route('informationOffice.edit', $user->id) }}"
                                                class="btn btn-sm btn-primary">ویرایش</a>
                                        @endcan
                                        @can('delete-offices')
                                            <form action="informationOffice.destroy" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mr-1">حذف</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">

                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
