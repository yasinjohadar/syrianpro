<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th>#</th><th>الشركة</th><th>القطاع</th><th>الوظائف</th><th>التقييم</th><th>الحالة</th><th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $company)
                    <tr>
                        <td>{{ $companies->firstItem() + $loop->index }}</td>
                        <td>
                            <a href="{{ route('admin.companies.edit', $company) }}" class="fw-bold row-title-link">{{ $company->name }}</a>
                            <div class="text-muted fs-11">{{ $company->location }}</div>
                        </td>
                        <td>{{ $company->sector }}</td>
                        <td><span class="badge-soft badge-soft-info">{{ $company->jobs_count_label }}</span></td>
                        <td>⭐ {{ $company->rating_display }}</td>
                        <td><span class="badge-soft badge-soft-{{ $company->is_active ? 'success' : 'secondary' }}">{{ $company->is_active ? 'نشط' : 'غير نشط' }}</span></td>
                        <td>
                            <div class="action-btn-group">
                                <a href="{{ route('companies.show', $company) }}" target="_blank" class="action-btn action-btn--view"><i class="ri-eye-line"></i></a>
                                <a href="{{ route('admin.companies.edit', $company) }}" class="action-btn action-btn--edit"><i class="ri-pencil-line"></i></a>
                                <form action="{{ route('admin.companies.toggle-active', $company) }}" method="POST" class="d-inline">@csrf<button type="submit" class="action-btn action-btn--success"><i class="ri-checkbox-circle-line"></i></button></form>
                                <button type="button" class="action-btn action-btn--delete" data-bs-toggle="modal" data-bs-target="#deleteCompanyModal" data-company-id="{{ $company->id }}" data-company-name="{{ $company->name }}"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-sm">إضافة شركة</a></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($companies->hasPages())<div class="card-footer">{{ $companies->links() }}</div>@endif
</div>
