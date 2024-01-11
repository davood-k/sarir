@extends('welcome')

@section('mohtava')
    <form class="m-5" action="{{ route('import') }}" method="post" enctype="multipart/form-data">
        @csrf

        <input class="btn btn-danger" type="file" name="file" class="form-control">

        <input class="btn btn-info" type="submit" value="upload" name="submit">

    </form>

@endsection
