@extends('welcome')

@section('mohtava')
    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title text-danger">لیست کارهای در دست اجرا</h3>

            <div class="row card-tools">

                <div class="btn-group-sm mr-1">
                    <a href="{{ route('duty.create') }}" class="btn btn-info">ایجاد وظیفه جدید</a>
                </div>
            </div>

        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>عنوان</th>
                        <th>تاریخ</th>
                        <th>تعداد</th>
                        <th>نام</th>
                        <th>بازه زمانی</th>
                        <th>انقضاء</th>
                        <th>توضیحات</th>
                        <th>اهمیت</th>
                        <th>اقدامات</th>
                    </tr>


                    @foreach ($list as $user)
                        <?php
                        $temp = \App\User::find($user->user_id);
                        ?>
                        @if ($user->user_id == auth()->user()->id)
                            <tr>
                                <td>{{ $user->title }}</td>
                                <td style="width: 10%">{{ $user->date }}</td>
                                <td>{{ $user->numbers }}</td>
                                <td>{{ $temp->name }}</td>
                                <td>{{ $user->span }}</td>
                                <td style="width: 10%">{{ $user->expires }}</td>
                                <td style="width: 30%">{{ $user->descriptions }}</td>
                                @if ($user->importantrange == '5')
                                    <td>خیلی مهم</td>
                                @elseif($user->importantrange == '4')
                                    <td>مهم</td>
                                @elseif($user->importantrange == '3')
                                    <td>متوسط</td>
                                @elseif($user->importantrange == '2')
                                    <td>کم</td>
                                @elseif($user->importantrange == '1')
                                    <td>خیلی کم</td>
                                @endif
                                <td>
                                    <form action="{{ route('duty.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="btn-group btn-group-xs">
                                            <a href="{{ route('duty.edit', $user->id) }}"
                                                class="btn btn-sm btn-primary">ویرایش</a>
                                            <button type="submit" class="btn btn-sm btn-danger mr-1">حذف</button>
                                        </div>
                                    </form>

                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->

    </div>
    <!-- /.card -->
    </div>
@endsection
