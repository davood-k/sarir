@extends('welcome')

@section('mohtava')
    <div class="col-9">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">لیست خروجی جهت نامه های معرفی</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('export') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <textarea id="expex" name="expexcelsr" class="form-control" style="height: 500px"></textarea>
                    </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="float-left">
                    <button type="submit" class="btn btn-success"><i class="fa fa-envelope-o"></i> دریافت خروجی</button>
                </div>
                <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> پاک کردن لیست</button>
            </div>
            </form>
            <!-- /.card-footer -->
        </div>
        <!-- /. box -->
    </div>

    </div>
    <!-- /.card -->
    </div>

@endsection
