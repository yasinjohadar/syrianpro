@php $tagColor = $tag->color ?? '#6366f1'; @endphp
<x-admin.confirm-modal
    :id="'deleteTag' . $tag->id"
    title="تأكيد حذف الوسم"
    message="سيتم إزالة الوسم من جميع المقالات المرتبطة به. لا يمكن التراجع عن هذا الإجراء."
    :subject="'#' . $tag->name"
    :subject-meta="$tag->posts_count . ' مقال مرتبط'"
    icon="ri-price-tag-3-line"
    :action="route('admin.blog.tags.destroy', $tag->id)"
    method="DELETE"
    confirm-text="نعم، احذف الوسم"
/>
