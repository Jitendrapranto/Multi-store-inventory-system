<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerNameUpdate">

                                <label class="form-label mt-3">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmailUpdate">

                                <label class="form-label mt-3">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobileUpdate">

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>


<script>



    async function FillUpUpdateForm(id){
        showLoader();
        
        try{
            document.getElementById('updateID').value=id;

            let res=await axios.post("/customer-by-id",{
                id
            });

            hideLoader();
            document.getElementById('customerNameUpdate').value=res.data.data['name'];
            document.getElementById('customerEmailUpdate').value=res.data.data['email'];
            document.getElementById('customerMobileUpdate').value=res.data.data['mobile'];

        } catch (error) {
            hideLoader();
            errorToast("Failed to load");
            console.error(error);
        }
    }


    async function Update() {

        try{
            let customerName = document.getElementById('customerNameUpdate').value;
            let customerEmail = document.getElementById('customerEmailUpdate').value;
            let customerMobile = document.getElementById('customerMobileUpdate').value;
            let updateID = document.getElementById('updateID').value;


            if (customerName.length === 0) {
                errorToast("Customer Name Required !")
            }

            else if(customerEmail.length===0){
                errorToast("Customer Email Required !")
            }

            else if(customerMobile.length===0){
                errorToast("Customer Mobile Required !")
            }

            else {
                document.getElementById('update-modal-close').click();
                // $('#update-modal').modal('hide');

                showLoader();

                let res = await axios.post("/update-customer",{
                    name: customerName,
                    email: customerEmail,
                    mobile: customerMobile,
                    id: updateID
                });

                // console.log(res);
            
                hideLoader();

                if(res.status===200 && res.data.status=== 'success'){
                    successToast('Request completed');
                    document.getElementById("update-form").reset();
                    await getList();
                }
                else{
                    errorToast("Request fail !")
                }
            }
        } catch (err) {
            hideLoader();
            if (err.response && err.response.status === 422) {
                let errors = err.response.data.errors;
                
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        errorToast(errors[field][0]);
                    }
                }
            } else if (err.response && err.response.status === 404) {
                errorToast(err.response.data.message);
                
            } else {
                errorToast(err.response.data.message);
            }
        }
    }

</script>
