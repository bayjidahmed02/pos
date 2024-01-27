<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>User Profile</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input readonly id="email" placeholder="User Email" class="form-control"
                                    type="email" />
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
                                <input id="mobile" placeholder="Mobile" class="form-control" type="tel" />
                            </div>
                            {{-- <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="Password" class="form-control" type="password" />
                            </div> --}}
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  bg-gradient-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    getProfile();
    async function getProfile() {
        showLoader();
        let res = await axios.get("/profile-details");
        hideLoader();
        if (res.status === 200 && res.data.status === 'success') {
            let data = res.data['data'];
            document.getElementById('email').value = data['email'];
            document.getElementById('firstName').value = data['firstName']
            document.getElementById('lastName').value = data['lastName']
            document.getElementById('mobile').value = data['mobile']
        } else {
            hideLoader();
            unauthorized(e.response.status)
            console.log(res.data.message);
        }
    }
    async function onUpdate() {
        let firstName = document.getElementById('firstName').value;
        let lastName = document.getElementById('lastName').value;
        let mobile = document.getElementById('mobile').value;

        if (firstName.length === 0) {
            errorToast('First name is required')
        } else if (lastName.length === 0) {
            errorToast('Last name is required')
        } else if (mobile.length === 0) {
            errorToast('Mobile number is required')
        } else {
            showLoader();
            try {
                let res = await axios.post('/update', {
                    firstName: firstName,
                    lastName: lastName,
                    mobile: mobile
                });
                hideLoader();
                if (res.status === 200 && res.data.status === 'success') {
                    successToast(res.data['message']);
                    await getProfile();
                } else {
                    errorToast(res.status.message)
                }
            } catch (e) {
                hideLoader()
                errorToast(res.status.message)
            }
        }
    }
</script>
