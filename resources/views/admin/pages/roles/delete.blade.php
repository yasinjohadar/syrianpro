<x-admin.confirm-modal
    :id="'delete' . $role->id"
    title="تأكيد حذف الدور"
    message="لا يمكن التراجع عن هذا الإجراء. سيتم حذف الدور وإزالة جميع الصلاحيات المرتبطة به."
    :subject="$role->name"
    :subject-meta="$role->permissions_count ? $role->permissions_count . ' صلاحية مرتبطة' : null"
    icon="ri-shield-cross-line"
    :action="route('admin.roles.destroy', $role->id)"
    method="DELETE"
    confirm-text="نعم، احذف الدور"
/>
