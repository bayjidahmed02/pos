<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Registration</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control"
                                    type="password" />
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onRegistration()"
                                    class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
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
        let firstName = document.getElementById('firstName').value;
        let lastName = document.getElementById('lastName').value;
        let email = document.getElementById('email').value;
        let mobile = document.getElementById('mobile').value;
        let password = document.getElementById('password').value;

        if (firstName.length === 0 || lastName.length === 0 || email.length === 0 || mobile.length === 0 || password
            .length === 0) {
            errorToast('All fields are required');
        } else {
            showLoader();
            try {
                let res = await axios.post('/registration', {
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    mobile: mobile,
                    password: password
                });
                hideLoader();
                if (res.status === 200 && res.data.status === 'success') {
                    successToast(res.data.message);
                    setTimeout(() => {
                        debugger
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    errorToast(res.data.message);
                }
            } catch (error) {
                hideLoader();
                console.error(error);
                errorToast('Something went wrong. Please try again.');
            }
        }
    }
</script>
