<x-admin.confirm-modal
    :id="'delete' . $user->id"
    title="تأكيد حذف المستخدم"
    message="لا يمكن التراجع عن هذا الإجراء. سيتم حذف حساب المستخدم وجميع البيانات المرتبطة به نهائياً."
    :subject="$user->name"
    :subject-meta="$user->email"
    :avatar="$user->photo ? asset('storage/' . $user->photo) : null"
    :avatar-initial="mb_strtoupper(mb_substr($user->name, 0, 1))"
    :action="route('admin.users.destroy', $user->id)"
    method="DELETE"
    confirm-text="نعم، احذف المستخدم"
/>
