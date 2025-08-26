<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Sign Up</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onRegistration()" class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function onRegistration() {
        let email = document.getElementById('email').value;
        let first_Name = document.getElementById('firstName').value;
        let last_Name = document.getElementById('lastName').value;
        let mobile = document.getElementById('mobile').value;
        let password = document.getElementById('password').value;

        if (email.length === 0) {
            errorToast('Email is required');
        } else if (first_Name.length === 0) {
            errorToast('First Name is required');
        } else if (last_Name.length === 0) {
            errorToast('Last Name is required');
        } else if (mobile.length === 0) {
            errorToast('Mobile is required');
        } else if (password.length === 0) {
            errorToast('Password is required');
        } else {
            showLoader();
            try {
                let res = await axios.post('/user-registration', {
                    email: email,
                    first_Name: first_Name,
                    last_Name: last_Name,
                    mobile: mobile,
                    password: password
                });

                hideLoader();

                if (res.status === 200 && res.data.status === 'success') {
                    successToast(res.data.message);
                    setTimeout(function () {
                        window.location.href = '/userLogin';
                    }, 2000);
                } else {
                    errorToast(res.data.message);
                }

            } catch (err) {
                hideLoader();
                if (err.response && err.response.status === 422) {
                    // Show each validation error
                    let errors = err.response.data.errors;
                    for (let field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            errorToast(errors[field][0]);
                        }
                    }
                } else {
                    errorToast("An unexpected error occurred");
                }
            }
        }
    }
    </script>
