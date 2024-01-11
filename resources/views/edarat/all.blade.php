@extends('welcome')

@section('mohtava')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">کاربران</h3>

                <div class="card-tools d-flex">
                    <form action="">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="جستجو" value="{{ request('search') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="btn-group-sm mr-1">
                        <a href="" class="btn btn-info">ایجاد بخش جدید</a>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>نام بخش</th>
                            <th>آدرس</th>
                            <th>شماره تماس</th>
                            <th>رابط</th>
                            <th>معاونت</th>
                            <th>اقدامات</th>
                        </tr>

                        @foreach($results as $user)
                                <tr>
                                    <td>{{ $user->tashKhsr }}</td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->moavenat }}</td>
                                    <td class="d-flex">
                                        <form action="" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ml-1">حذف</button>
                                        </form>
                                            <a href="" class="btn btn-sm btn-primary">ویرایش</a>
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