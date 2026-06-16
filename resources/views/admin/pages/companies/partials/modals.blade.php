<div class="modal fade" id="deleteCompanyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteCompanyForm">@csrf @method('DELETE')
                <div class="modal-body text-center">
                    <h4 class="text-danger fw-bold">حذف الشركة</h4>
                    <p id="deleteCompanyName"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
