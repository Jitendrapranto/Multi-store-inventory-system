<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Category</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 bg-gradient-info">Create</button>
                </div>
            </div>
            <hr class="bg-secondary"/>
            <div class="table-responsive">
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>No</th>
                    <th>Category</th>
                    <th class="text-end">Action</th>
                </tr>
                </thead>
                <tbody id="tableList">

                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>



<script>

getList();


    async function getList() {
    try {
        showLoader();

        const res = await axios.get("/all-category");

        hideLoader();

        const categories = res.data.data;

        const tableList = $("#tableList");
        const tableData = $("#tableData");

        // Reset table
        if ($.fn.DataTable.isDataTable('#tableData')) {
            tableData.DataTable().destroy();
        }

        tableList.empty();

        // Populate table
        categories.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
                            <div class="btn-group" role="group">
                                <button data-id="${item.id}" class="float-end btn editBtn btn-sm btn-info">Edit</button>
                                <button data-id="${item.id}" class="float-end btn deleteBtn btn-sm btn-primary">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>`;
            tableList.append(row);
        });

        // Re-bind event listeners
        $(".editBtn").off('click').on('click', async function () {
            const id = $(this).data('id');
            await FillUpUpdateForm(id);
            $("#update-modal").modal('show');
        });

        $(".deleteBtn").off('click').on('click', function () {
            const id = $(this).data('id');
            $("#deleteID").val(id);
            $("#delete-modal").modal('show');
        });

        // Re-initialize DataTable
        new DataTable('#tableData', {
            order: [[0, 'desc']],
            lengthMenu: [5, 10, 15, 20, 30]
        });

    } catch (error) {
            hideLoader();
            errorToast("Failed to fetch category list");
            console.error("getList() error:", error);
        }
    }

</script>