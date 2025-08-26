@extends('layouts.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Phone:  <span id="cPhone"></span></p>
                            <p class="text-xs mx-0 my-1">Customer ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{"images/logo.png"}}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice  </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('d-m-Y') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                <tr class="text-xs">
                                    <td>Name</td>
                                    <td>Qty</td>
                                    <td>Subtotal</td>
                                    <td>Remove</td>
                                </tr>
                                </thead>
                                <tbody  class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                       <div class="col-12">
                           <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span id="total"></span></p>
                           
                           <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>  <span id="vat"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>  <span id="discount"></span></p>
                           <span class="text-xxs">Discount(%):</span>
                           <input onkeydown="return false" value="0" min="0" type="number" step="0.25" onchange="DiscountChange()" class="form-control w-40 " id="discountP"/>
                           <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>  <span id="payable"></span></p>
                           <p>
                              <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                           </p>
                       </div>
                        <div class="col-12 p-2">

                        </div>

                    </div>
                </div>
            </div>
            {{-- Product List --}}
            <div class="col-md-4 col-lg-4 p-2">
                
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">                    
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Product</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>
            
            
            <div class="col-md-4 col-lg-4 p-2">                
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="m-0">Customer</h6>
                <button data-bs-toggle="modal" data-bs-target="#create-modal" class="btn btn-sm bg-gradient-info">New Customer</button>
            </div>

            {{-- Customer List --}}
            <table class="table table-sm w-100" id="customerTable">                        
                <thead>
                    <tr class="text-xs text-bold">
                        <th>Name</th>
                        <th>Pic</th>
                    </tr>
                </thead>
                <tbody id="customerList">
                    <!-- Customer data will be injected here -->
                </tbody>
            </table>                    
        </div>
    </div>      


    {{-- Create Customer Modal --}}
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
                    <button id="customer-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="salePageNewCustomer()" id="update-btn" class="btn bg-gradient-success">Save</button>
                </div>
            </div>
        </div>
    </div>



    {{-- Product Selecting Modal --}}

    <div class="modal animated zoomIn" id="sale-product-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3 p-1">
                                    <label class="form-label">Product ID *</label>
                                    <input type="text" class="form-control" id="PId" readonly>
                                </div>
                                <div class="col-md-9 p-1">
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" id="PName" readonly>
                                </div>
                                <div class="col-md-4 p-1">
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="PPrice" readonly>
                                    <input type="text" class="form-control d-none" id="PBuyPrice" readonly>
                                </div>

                                <div class="col-md-3 p-1">
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input type="text" class="form-control" id="PQty" value="1">
                                </div>
                                <div class="col-md-5 p-1">
                                    <label class="form-label mt-2">Discount (Per/Product) *</label>
                                    <input type="text" class="form-control" id="PDiscount" value="0">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-info" >Add</button>
                </div>
            </div>
        </div>
    </div>   


    <script>


        (async ()=>{
          showLoader();
          await  CustomerList();
          await ProductList();
          hideLoader();
        })()


        let InvoiceItemList=[];


        function ShowInvoiceItem() {

            let invoiceList=$('#invoiceList');

            invoiceList.empty();

            InvoiceItemList.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td>${item['product_name']}</td>
                        <td>${item['qty']}</td>
                        <td>${item['subtotal']}</td>

                        <td><a data-index="${index}"
                            data-productID="${item['product_id']}"
                            data-itemDiscount="${item['item_discount']}"
                            data-unitPrice="${item['price_with_discount']}" 
                            class="btn remove text-xxs px-2 py-1  btn-sm m-0">Remove</a>
                        </td>
                    </tr>`
                invoiceList.append(row)
            })

            CalculateGrandTotal();

            $('.remove').on('click', async function () {
                let index= $(this).data('index');
                removeItem(index);
            })

        }


        function removeItem(index) {
            InvoiceItemList.splice(index,1);
            ShowInvoiceItem()
        }

        function DiscountChange() {
            CalculateGrandTotal();
        }

        function CalculateGrandTotal(){
            let Total=0;
            let Vat=0;
            let Payable=0;
            let Discount=0;
           
            let discountPercentage=(parseFloat(document.getElementById('discountP').value));

            InvoiceItemList.forEach((item,index)=>{
                Total=Total+parseFloat(item['subtotal']);
            })

             if(discountPercentage===0){
                 Vat= ((Total*5)/100).toFixed(2);
             }
             else {
                 Discount=((Total*discountPercentage)/100).toFixed(2);
                 Total=(Total-((Total*discountPercentage)/100)).toFixed(2);
                 Vat= ((Total*5)/100).toFixed(2);
             }

             Payable=(parseFloat(Total)+parseFloat(Vat)).toFixed(2);


            document.getElementById('total').innerText=Total;
            document.getElementById('payable').innerText=Payable;
            document.getElementById('vat').innerText=Vat;
            document.getElementById('discount').innerText=Discount;
        }


        function add() {
           let PId= document.getElementById('PId').value;
           let PName= document.getElementById('PName').value;
           let PPrice=document.getElementById('PPrice').value;
           let BuyPrice=document.getElementById('PBuyPrice').value;
           let PQty= document.getElementById('PQty').value;
           let PDiscount= document.getElementById('PDiscount').value;
           let UnitPrice=(parseFloat(PPrice) - parseFloat(PDiscount)).toFixed(2);
           let SubTotalPrice=(parseFloat(UnitPrice)*parseFloat(PQty)).toFixed(2);
           let ItemProfit=(parseFloat(BuyPrice)*parseFloat(PQty) - parseFloat(SubTotalPrice)).toFixed(2);
           if(PId.length===0){
               errorToast("Product ID Required");
           }
           else if(PName.length===0){
               errorToast("Product Name Required");
           }
           else if(PPrice.length===0){
               errorToast("Product Price Required");
           }
           else if(PQty.length===0){
               errorToast("Product Quantity Required");
           }
           else{
               let item={
                    product_name: PName,
                    product_id: PId,
                    qty: PQty,
                    item_discount: PDiscount,
                    price_with_discount: UnitPrice,
                    subtotal: SubTotalPrice,
                    item_profit: ItemProfit
                };
               InvoiceItemList.push(item);
               console.log(InvoiceItemList);
               $('#sale-product-modal').modal('hide')
               ShowInvoiceItem();
           }
        }


        function addModal(id,name,price,BuyPrice) {
            document.getElementById('PId').value=id
            document.getElementById('PName').value=name
            document.getElementById('PPrice').value=price
            document.getElementById('PBuyPrice').value=BuyPrice
            $('#sale-product-modal').modal('show')
        }


        async function CustomerList(){
            let res=await axios.get("/all-customer");
            let customerList=$("#customerList");
            let customerTable=$("#customerTable");

            customerTable.DataTable().destroy();
            customerList.empty();

            res.data.data.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td><i class="bi bi-person"></i> ${item['name']}</td>
                        <td><a data-name="${item['name']}" data-mobile="${item['mobile']}" data-id="${item['id']}" class="btn btn-outline-dark addCustomer  text-xxs px-2 py-1  btn-sm m-0">Add</a></td>
                     </tr>`
                customerList.append(row)
            })


            $('.addCustomer').on('click', async function () {

                let CName= $(this).data('name');
                let cPhone= $(this).data('mobile');
                let CId= $(this).data('id');

                $("#CName").text(CName)
                $("#cPhone").text(cPhone)
                $("#CId").text(CId)

            })

            new DataTable('#customerTable',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }


        async function ProductList(){
            let res=await axios.get("/product-with-stock");
            let productList=$("#productList");
            let productTable=$("#productTable");
            productTable.DataTable().destroy();
            productList.empty();

            res.data.data.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td> <img class="w-10" src="${item['img_url']}"/> ${item['name']} ($ ${item['price']})</td>
                        <td><a data-name="${item['name']}"                            
                            data-buyprice="${item['buy_price']}"
                            data-price="${item['price']}" 
                            data-id="${item['id']}" 
                            class="btn btn-outline-dark text-xxs px-2 py-1 addProduct  btn-sm m-0">Add</a>
                        </td>
                    </tr>`
                productList.append(row);
                // console.log(res.data.data);
            })
            


            $('.addProduct').on('click', async function () {
                let PName= $(this).data('name');
                let PPrice= $(this).data('price');
                let PId= $(this).data('id');
                let BuyPrice= $(this).data('buyprice');
                addModal(PId,PName,PPrice,BuyPrice);
                // console.log(BuyPrice);
            })


            new DataTable('#productTable',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }


        async  function createInvoice() {
            let total=document.getElementById('total').innerText;
            let discount=document.getElementById('discount').innerText
            let vat=document.getElementById('vat').innerText
            let payable=document.getElementById('payable').innerText
            let CId=document.getElementById('CId').innerText;
            let TotalProfit=0.0;

            InvoiceItemList.forEach((item)=>{
                let profit = parseFloat(item['item_profit']);
                if (!isNaN(profit)) {
                    TotalProfit += profit;
                }
            });


            let Data = {
                total: parseFloat(total),
                discount: parseFloat(discount),
                vat: parseFloat(vat),
                payable: parseFloat(payable),
                customer_id: parseInt(CId),
                total_profit: parseFloat(TotalProfit),
                products: InvoiceItemList
            };


            if(CId.length===0){
                errorToast("Customer Required !")
            }
            else if(InvoiceItemList.length===0){
                errorToast("Product Required !")
            }
            else{

                showLoader();
                let res=await axios.post("/create-invoice",Data)
                hideLoader();
                if(res.status===200){
                    window.location.href='/invoicePage'
                    successToast("Invoice Created");
                }
                else{
                    errorToast("Something Went Wrong")
                }
            }

        }

        async function salePageNewCustomer(){
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
                    document.getElementById('customer-modal-close').click();
                    await CustomerList();
                } else {
                    errorToast("Request failed!");
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




@endsection
