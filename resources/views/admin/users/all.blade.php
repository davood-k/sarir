@extends('welcome')

@section('mohtava')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div class="page-header head-section">
            <h2>کاربران</h2>
            <div class="btn-group">
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-info">سطوح دسترسی</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>نام کاربر</th>
                        <th>ایمیل</th>
                        <th>وضعیت ایمیل</th>
                        <th>تنظیمات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>0</td>
                            <td>
                                @can('delete-person' , $user)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                        {{ method_field('delete') }}
                                        {{ csrf_field() }}
                                        <div class="btn-group btn-group-xs">
                                            <button type="submit" class="btn btn-danger">حذف</button>
                                        </div>
                                    </form>
                                @endcan
                                <a class="btn btn-sm btn-primary ml-2" href="{{ route('users.edit' , ['user' => $user->id ]) }}">ويرايش</a>
                                
                                <a class="btn btn-sm btn-warning" href="{{ route('users.permissions' , $user->id) }}">دسترسی</a>
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="text-align: center">
            {!! $users->render() !!}
        </div>
    </div>
@endsection
