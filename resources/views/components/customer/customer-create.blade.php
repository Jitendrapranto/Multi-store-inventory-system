<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Customer</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerNameCreate">
                                <label class="form-label">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmailCreate">
                                <label class="form-label">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobileCreate">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="update-btn" class="btn bg-gradient-success">Save</button>
                </div>
            </div>
    </div>
</div>


<script>
    async function Save(){
        let nameInput = document.getElementById('customerNameCreate').value;
        let emailInput = document.getElementById('customerEmailCreate').value;
        let mobileInput = document.getElementById('customerMobileCreate').value;

        // if (!nameInput || !emailInput || !mobileInput) {
        //     errorToast("Form input(s) not found!");
        //     return;
        // }

   
        if (nameInput.length === 0) {
            errorToast("Customer Name Required!");
            return;
        }
        if (emailInput.length === 0) {
            errorToast("Customer Email Required!");
            return;
        }

        if (mobileInput.length === 0) {
            errorToast("Customer Mobile Required!");
            return;
        }       

        try {

            showLoader();
            let res = await axios.post("/create-customer", {
                name: nameInput,
                email: emailInput,
                mobile: mobileInput
            });

            hideLoader();

            if (res.status === 200 && res.data.status === 'success') {
                successToast(res.data.message || "Customer Created!");
                document.getElementById("save-form").reset();
                await getList();
            } else {
                errorToast("Request failed!");
            }
            document.getElementById('modal-close').click();

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