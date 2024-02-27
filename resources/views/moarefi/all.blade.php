@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">مجرد - عدم انتقال به سایر بخش ها</h3>

            <div class="row card-tools">

                <form class="d-flex" action="">
                    @can("add-user")
                    <a href="/insert" class="m-1">
                        <img src="/dist/img/iconsperson.png" alt="">
                    </a>
                    @endcan
                    <a href="/" class="btn btn-default btn-default-sm ml-2">
                        <i class="fa fa-refresh" area-hidden= "true"></i>
                    </a>
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
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>کدملی</th>
                        <th>معرفی نامه از</th>
                        <th style="width: 15%;">ملاحظات</th>
                        <th>ثبت کننده</th>
                        <th>اقدامات</th>
                    </tr>

                    @foreach ($all as $user)
                        <tr>
                            <td>
                                {{ $user->fname }}
                            </td>
                            <td>
                                {{ $user->lname }}
                            </td>
                            <td>
                                {{ $user->codemelli }}
                            </td>

                            <?php
                            $temp = \App\Khademyar::find($user->id);
                            $khadem = $temp->definations->where('deleted', '0')->all();
                            
                            ?>

                            <td>
                                @foreach ($khadem as $item)
                                    {{ 'نامه ' . $item->sh_letter . ' - ' . $item->date_letter . ' || ' . $item->moarefi }}</br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($khadem as $item)
                                    {{ $item->tozih }}
                                @endforeach
                            </td>

                            <td>
                                @foreach ($khadem as $item)
                                    {{ $users->find($item->user_id)->name }}
                                @endforeach
                            </td>

                            <td class="d-flex">

                                <a class="btn btn-sm btn-info ml-2" data-toggle="modal"
                                    data-target=".myModal-{{ $user->id }}">مشاهده
                                    جزئیات</a>
                                <!-- Modal -->
                                <div class="modal fade mt-5 myModal-{{ $user->id }}" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="">

                                                <ul class="list-group list-group-flush mr-2">
                                                    @foreach ($khadem as $item)
                                                        <li class="list-group-item pl-0 d-flex">
                                                            {{ 'حسب نامه ' . $item->sh_letter . ' - ' . $item->date_letter . ' ' . $item->moarefi }}

                                                            @can('edit-user')
                                                                <form action="moarefi/store/{{ $item->id }}"
                                                                    method="get">
                                                                    <button class="btn btn-sm btn-warning mr-1" type="submit">
                                                                        و
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                            @can('delete-user')
                                                                <form action="delmoarefi/{{ $item->id }}" method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <button class="btn btn-sm btn-danger mr-1" type="submit">
                                                                        ح
                                                                    </button>
                                                                </form>
                                                                </br>
                                                            @endcan

                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <hr>

                                            </div>

                                        </div>
                                    </div>
                                    <a class="btn btn-sm btn-warning ml-2"
                                        href="{{ url('/person/create', $user->id) }}">ویرایش
                                        خادمیار</a>
                                    <form action="delete/{{ $user->id }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger ml-2" type="submit">
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
        <div class="card-footer">
            {{ $all->links() }}
        </div>
    </div>
    <!-- /.card -->
    </div>
@endsection
