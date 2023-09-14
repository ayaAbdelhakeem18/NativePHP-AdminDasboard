
function showDeleteModal(id) {
        var modalContent = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Row?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="?page=delete&id=${id}" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('deleteModal').innerHTML = modalContent;

        $('#deleteModal').modal('show');
}
