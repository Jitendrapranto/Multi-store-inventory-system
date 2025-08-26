<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 p-1">
                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label mt-2">Buy Price</label>
                                <input type="text" class="form-control" id="productBuyPriceUpdate">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label mt-2">Sale Price</label>
                                <input type="text" class="form-control" id="productPriceUpdate">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate">
                            </div>

                            <div class="col-md-6 p-1">
                                <label class="form-label mt-2">Stock Quantity</label>
                                <input type="text" class="form-control" id="productStockUpdate">
                            </div>

                            <div class="col-md-12 p-1">
                                <br/>
                                <img class="w-15" id="oldImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])"  type="file" class="form-control" id="productImgUpdate">

                                <input type="text" class="d-none" id="updateID">
                                <input type="text" class="d-none" id="filePath">


                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="update()" id="update-btn" class="btn bg-gradient-info" >Update</button>
            </div>

        </div>
    </div>
</div>


<script>



    async function UpdateFillCategoryDropDown(){
        let res = await axios.get("/all-category")
        res.data.data.forEach(function (item,i) {
            let option=`<option value="${item['id']}">${item['name']}</option>`
            $("#productCategoryUpdate").append(option);
        })
    }


    async function FillUpUpdateForm(id,filePath){

        document.getElementById('updateID').value=id;
        document.getElementById('filePath').value=filePath;
        document.getElementById('oldImg').src=filePath;


        showLoader();
        await UpdateFillCategoryDropDown();

        let res=await axios.post("/product-by-id",{id:id})
        hideLoader();

        document.getElementById('productNameUpdate').value=res.data.data['name'];
        document.getElementById('productBuyPriceUpdate').value=res.data.data['buy_price'];
        document.getElementById('productPriceUpdate').value=res.data.data['price'];
        document.getElementById('productUnitUpdate').value=res.data.data['unit'];
        document.getElementById('productStockUpdate').value=res.data.data['stock_qty'];
        document.getElementById('productCategoryUpdate').value=res.data.data['category_id'];

    }



    async function update() {

        let productCategoryUpdate= document.getElementById('productCategoryUpdate').value;
        let productNameUpdate = document.getElementById('productNameUpdate').value;
        let productBuyPriceUpdate = document.getElementById('productBuyPriceUpdate').value;
        let productPriceUpdate = document.getElementById('productPriceUpdate').value;
        let productUnitUpdate = document.getElementById('productUnitUpdate').value;
        let productStockUpdate = document.getElementById('productStockUpdate').value;
        let updateID= document.getElementById('updateID').value;
        let filePath= document.getElementById('filePath').value;
        let productImgUpdate = document.getElementById('productImgUpdate').files[0];


        if (productCategoryUpdate.length === 0) {
            errorToast("Product Category Required !")
            return;
        }
        if(productNameUpdate.length===0){
            errorToast("Product Name Required !")
            return;
        }
        if(productBuyPriceUpdate.length===0){
            errorToast("Buying Price Required !")
            return;
        }
        if(productPriceUpdate.length===0){
            errorToast("Product Price Required !")
            return;
        }
        if(productUnitUpdate.length===0){
            errorToast("Product Unit Required !")
            return;
        }
        if(productStockUpdate.length===0){
            errorToast("Product Stock Required !")
            return;
        }

        let formData=new FormData();
        formData.append('img', productImgUpdate);
        formData.append('id', updateID);
        formData.append('name', productNameUpdate);
        formData.append('buy_price', productBuyPriceUpdate);
        formData.append('price', productPriceUpdate);
        formData.append('unit', productUnitUpdate);
        formData.append('stock_qty', productStockUpdate);
        formData.append('category_id', productCategoryUpdate);
        formData.append('file_path', filePath);
        
        try{
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            showLoader();
            let res = await axios.post("/update-product",formData,config)
            hideLoader();

            if(res.status===200){
                successToast('Request completed');
                document.getElementById("save-form").reset();
                
                document.getElementById('update-modal-close').click();
                await getList();
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
