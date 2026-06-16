<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 260px;">العنوان</th>
                    <th style="min-width: 140px;">التصنيف</th>
                    <th style="min-width: 120px;">الكاتب</th>
                    <th style="min-width: 100px;">المشاهدات</th>
                    <th style="min-width: 100px;">الحالة</th>
                    <th style="min-width: 120px;">تاريخ النشر</th>
                    <th style="min-width: 180px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td class="text-muted fw-medium">{{ $posts->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . ltrim($post->featured_image, '/')) }}"
                                         alt="{{ $post->title }}"
                                         class="row-avatar row-avatar--img">
                                @else
                                    <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                        <i class="ri-article-line"></i>
                                    </span>
                                @endif
                                <div>
                                    <a href="{{ route('admin.blog.posts.edit', $post->id) }}"
                                       class="fw-bold row-title-link text-decoration-none d-block">
                                        {{ Str::limit($post->title, 55) }}
                                    </a>
                                    @if($post->is_featured)
                                        <span class="badge-soft badge-soft-warning fs-11 mt-1">
                                            <i class="ri-star-fill"></i> مميز
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($post->category)
                                <span class="badge-soft badge-soft-primary">
                                    @if($post->category->icon)
                                        <i class="{{ $post->category->icon }} me-1"></i>
                                    @endif
                                    {{ $post->category->name }}
                                </span>
                            @else
                                <span class="text-muted fs-12">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="meta-text">
                                <i class="ri-user-line"></i>
                                {{ $post->author?->name ?? 'غير محدد' }}
                            </span>
                        </td>
                        <td>
                            <span class="meta-text">
                                <i class="ri-eye-line"></i>
                                {{ number_format($post->views_count) }}
                            </span>
                        </td>
                        <td>
                            @if($post->status === 'published')
                                <span class="badge-soft badge-soft-success">منشور</span>
                            @elseif($post->status === 'draft')
                                <span class="badge-soft badge-soft-secondary">مسودة</span>
                            @else
                                <span class="badge-soft badge-soft-info">مجدول</span>
                            @endif
                        </td>
                        <td>
                            @if($post->published_at)
                                <span class="meta-text" title="{{ $post->published_at->locale('ar')->translatedFormat('j F Y') }}">
                                    <i class="ri-calendar-line"></i>
                                    {{ $post->published_at->format('Y-m-d') }}
                                </span>
                            @else
                                <span class="text-muted fs-12">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btn-group">
                                @if($post->status === 'published' && Route::has('frontend.blog.show'))
                                    <a href="{{ route('frontend.blog.show', $post->slug) }}"
                                       target="_blank" rel="noopener"
                                       class="action-btn action-btn--view" title="عرض">
                                        <i class="ri-external-link-line"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.blog.posts.edit', $post->id) }}"
                                   class="action-btn action-btn--edit" title="تعديل">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.blog.posts.toggle-featured', $post->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--key {{ $post->is_featured ? 'is-active' : '' }}"
                                            title="{{ $post->is_featured ? 'إزالة من المميز' : 'جعله مميز' }}">
                                        <i class="ri-star{{ $post->is_featured ? '-fill' : '-line' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.blog.posts.toggle-publish', $post->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn action-btn--success {{ $post->status === 'published' ? 'is-active' : '' }}"
                                            title="{{ $post->status === 'published' ? 'إلغاء النشر' : 'نشر' }}">
                                        <i class="ri-{{ $post->status === 'published' ? 'checkbox-circle-fill' : 'checkbox-circle-line' }}"></i>
                                    </button>
                                </form>
                                <button type="button"
                                        class="action-btn action-btn--delete"
                                        title="حذف"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deletePostModal"
                                        data-post-id="{{ $post->id }}"
                                        data-post-title="{{ Str::limit($post->title, 50) }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="ri-article-line"></i></div>
                                @if(request()->hasAny(['search', 'category', 'status', 'author']))
                                    <h5 class="fw-bold mb-2">لا توجد نتائج</h5>
                                    <p class="text-muted mb-3">لم يتم العثور على مقالات مطابقة للبحث.</p>
                                    <button type="button" class="btn btn-light border btn-sm" data-ajax-reset>
                                        <i class="ri-refresh-line me-1"></i> إعادة تعيين
                                    </button>
                                @else
                                    <h5 class="fw-bold mb-2">لا توجد مقالات</h5>
                                    <p class="text-muted mb-3">لم يتم إنشاء أي مقالات بعد.</p>
                                    <a href="{{ route('admin.blog.posts.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> إضافة مقال جديد
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($posts->hasPages())
        <div class="card-footer border-top bg-transparent py-3 ajax-pagination">
            {{ $posts->withQueryString()->links() }}
        </div>
    @endif
</div>
