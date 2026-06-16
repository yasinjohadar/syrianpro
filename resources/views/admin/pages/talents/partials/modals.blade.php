<div class="modal fade modal-user-action" id="deleteTalentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteTalentForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger"><i class="ri-delete-bin-7-line"></i></div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف الموهبة</h4>
                    <p class="action-modal-user mb-2" id="deleteTalentName"></p>
                </div>
                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" class="btn btn-light btn-lg px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger btn-lg px-4">نعم، احذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
