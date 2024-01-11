@extends('welcome')

@section('mohtava')
    @slot('script')
        <script>
            $('#roles').select2({
                'placeholder': 'مقام مورد نظر را انتخاب کنید'
            })
            $('#permissions').select2({})
        </script>
    @endslot
    @include('admin.layouts.errors')
    <div class="card col-12">
        <div class="card-header">
            <h3 class="card-title">ثبت دسترسی</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('users.permissions.store', $user->id) }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">مقام ها</label>
                    <select class="form-control" name="roles[]" id="roles" multiple>
                        @foreach (\App\Role::all() as $role)
                            <option value="{{ $role->id }}"
                                {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $role->name }} - {{ $role->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">دسترسی ها</label>
                    <select class="form-control" name="permissions[]" id="permissions" multiple>
                        @foreach (\App\Permission::all() as $permission)
                            <option value="{{ $permission->id }}"
                                {{ in_array($permission->id, $user->permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $permission->name }} - {{ $permission->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info">ثبت مقام</button>
                <a href="{{ route('roles.index') }}" class="btn btn-default float-left">لغو</a>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection
