@foreach ($users as $user)
    @include('admin.pages.users.delete')
    @include('admin.pages.users.change_password')
@endforeach
