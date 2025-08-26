<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 p-1">
                                <label class="form-label">Category</label>
                                <select class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="productName">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label">Buy Price</label>
                                <input type="text" class="form-control" id="buyPrice">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label">Sale Price</label>
                                <input type="text" class="form-control" id="productSalePrice">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label">Unit</label>
                                <input type="text" class="form-control" id="productUnit">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label">Stock Quantity</label>
                                <input type="text" class="form-control" id="productStock">
                            </div>
                                
                            <div class="col-md-12 p-1">
                                <br/>
                                    <img class="w-15" id="newImg" src="{{asset('images/default.jpg')}}" />
                                <br/>
                                <label class="form-label mt-2">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" type="file" class="form-control" id="productImg">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>


<script>



    FillCategoryDropDown();

    async function FillCategoryDropDown(){
        let res = await axios.get("/all-category")
        res.data.data.forEach(function (item,i) {
            let option=`<option value="${item['id']}">${item['name']}</option>`
            $("#productCategory").append(option);
        })
    }


    async function Save() {

        let productCategory=document.getElementById('productCategory').value;
        let productName = document.getElementById('productName').value;
        let buyPrice = document.getElementById('buyPrice').value;
        let productSalePrice = document.getElementById('productSalePrice').value;
        let productUnit = document.getElementById('productUnit').value;
        let productStock = document.getElementById('productStock').value;
        let productImg = document.getElementById('productImg').files[0];

        if (productCategory.length === 0) {
            errorToast("Product Category Required !")
            return;
        }
        
        if(productName.length===0){
            errorToast("Product Name Required !")
            return;
        }
        
        if(buyPrice.length===0){
            errorToast("Product Buy Price Required !")
            return;
        }
        if(productSalePrice.length===0){
            errorToast("Product Sell Price Required !")
            return;
        }

        if(productUnit.length===0){
            errorToast("Product Unit Required !")
            return;
        }

        if(productStock.length===0){
            errorToast("Product Stock Required !")
            return;
        }

        if(!productImg){
            errorToast("Product Image Required !")
            return;
        }

        let formData = new FormData();
        formData.append('img', productImg);
        formData.append('name', productName);
        formData.append('buy_price', buyPrice);
        formData.append('price', productSalePrice);
        formData.append('unit', productUnit);
        formData.append('stock_qty', productStock);
        formData.append('category_id', productCategory);


        try {
            showLoader();

            let config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            };
            
            let res = await axios.post("/create-product",formData,config)
            hideLoader();

            if(res.status===200){
                successToast('Request completed');
                document.getElementById("save-form").reset();
                await getList();
                document.getElementById('modal-close').click();
            }
            else{
                errorToast("Request fail !");
            }
        }catch (err) {
            hideLoader();
            if (err.response && err.response.status === 422) {
                let errors = err.response.data.error;
                
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        errorToast(errors[field][0]);
                    }
                }
            
            } else {
                errorToast(err.response.data.message);
            }
        }
    }
</script>
