@extends('welcome')


@section('mohtava')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div class="page-header head-section">
            <h2>ویرایش دسترسی</h2>
        </div>
        <form class="form-horizontal" action="{{ route('permissions.update' ,  $permission->id ) }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            @include('Admin.layouts.errors')
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="name" class="control-label">عنوان دسترسی</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="عنوان را وارد کنید" value="{{ old('name' , $permission->name) }} ">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="label" class="control-label">توضیحات کوتاه</label>
                    <textarea rows="5" class="form-control" name="label" id="label" placeholder="توضیحات را وارد کنید">{{ old('label' , $permission->label) }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-danger">ویرایش</button>
                </div>
            </div>
        </form>
    </div>
@endsection