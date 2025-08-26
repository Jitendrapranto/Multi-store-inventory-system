<div class="modal animated zoomIn" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class=" mt-3 text-warning">Delete !</h3>
                {{-- <input type="text" class="form-control d-none" id="customerName"> --}}
                <p class="mb-3">
                    Do you want to delete: <br> <strong id="customerName" class="text-danger"></strong> ?
                </p>                
                <input class="d-none" id="deleteID"/>

            </div>
            <div class="modal-footer justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn mx-2 bg-gradient-primary" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="itemDelete()" type="button" id="confirmDelete" class="btn  bg-gradient-danger" >Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    async function ShowName(id){
        showLoader();
        
        try{
            document.getElementById('updateID').value=id;

            let res=await axios.post("/customer-by-id",{
                id
            });

            hideLoader();

            let name = res.data.data['name'];
            document.getElementById('customerName').textContent = name;
           
        } catch (error) {
            hideLoader();
            errorToast("Failed to load");
            console.error(error);
        }
    }


    async  function  itemDelete(){
        try{
            let id=document.getElementById('deleteID').value;

            document.getElementById('delete-modal-close').click();

            showLoader();

            let res=await axios.post("/delete-customer",{id:id})

            hideLoader();

            if(res.status===200 && res.data.status=== 'success'){
                successToast('Delete Successful');
                document.getElementById("update-form").reset();
                await getList();
            } else{
                errorToast("Request fail !")
            }
        }catch (err) {
            hideLoader();
            if (err.response && err.response.status === 404) {
                errorToast(err.response.data.message);
            } else {
                // errorToast("Something went wrong");
                errorToast(err.response.data.message);
            }
        }
    }
</script>
