<div class="modal fade modal-user-action" id="deleteSpecialtyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteSpecialtyForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف التخصص</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف التخصص</h4>
                    <p class="action-modal-user mb-2" id="deleteSpecialtyName"></p>
                    <p class="text-muted mb-0 px-md-4">
                        لا يمكن التراجع عن هذا الإجراء. سيتم حذف التخصص نهائياً.
                    </p>
                </div>
                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" class="btn btn-light btn-lg px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-danger btn-lg px-4">
                        <i class="ri-delete-bin-line me-1"></i> نعم، احذف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
