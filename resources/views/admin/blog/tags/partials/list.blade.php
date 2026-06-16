<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 180px;">الاسم</th>
                    <th style="min-width: 130px;">عدد المقالات</th>
                    <th style="min-width: 140px;">اللون</th>
                    <th style="min-width: 100px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                    @php $tagColor = $tag->color ?? '#6366f1'; @endphp
                    <tr>
                        <td class="text-muted fw-medium">{{ $tags->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="tag-name-badge" style="--tag-color: {{ $tagColor }}">
                                <i class="ri-hashtag"></i>{{ $tag->name }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-info">
                                <i class="ri-article-line me-1"></i>{{ number_format($tag->posts_count) }} مقال
                            </span>
                        </td>
                        <td>
                            <span class="tag-color-chip">
                                <span class="tag-color-chip__dot" style="background-color: {{ $tagColor }}"></span>
                                <span dir="ltr" class="tag-color-chip__hex">{{ $tag->color ?? '—' }}</span>
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('admin.blog.tags.edit', $tag->id) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteTag{{ $tag->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-price-tag-3-line"></i></div>
                                @if(request()->hasAny(['search', 'sort']))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على وسوم مطابقة للبحث.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا توجد وسوم</h5>
                                    <p class="text-muted mb-3">لم يتم إنشاء أي وسوم بعد.</p>
                                    <a href="{{ route('admin.blog.tags.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة وسم جديد
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tags->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $tags->withQueryString()->links() }}
        </div>
    @endif
</div>
