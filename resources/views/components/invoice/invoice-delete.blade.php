<div class="modal animated zoomIn" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class=" mt-3 text-warning">Delete !</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="d-none" id="deleteID"/>
            </div>
            <div class="modal-footer justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn bg-gradient-success" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="itemDelete()" type="button" id="confirmDelete" class="btn bg-gradient-danger" >Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async  function  itemDelete(){
        let Id=document.getElementById('deleteID').value;
        document.getElementById('delete-modal-close').click();
        try {
            showLoader();
            let res = await axios.post("/delete-invoice", { inv_id: Id });
            hideLoader();
            
            if (res.status === 200 && res.data.status === "success") {
                successToast("Request completed");
                await getList();
            } else {
                errorToast("Request failed!");
            }
        } catch (err) {
            hideLoader();
            errorToast("Server error");
            console.error(err);
        }            
    }
    
</script>