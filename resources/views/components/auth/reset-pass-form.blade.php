<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <h4>SET NEW PASSWORD</h4>
                    <br />
                    <label>New Password</label>
                    <input id="password" placeholder="New Password" class="form-control" type="password" />
                    <br />
                    <label>Confirm Password</label>
                    <input id="confirmPassword" placeholder="Confirm Password" class="form-control" type="password" />
                    <br />
                    <button onclick="ResetPass()" class="btn w-100  btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    async function ResetPass() {
        let password = document.getElementById('password').value
        let confirmPassword = document.getElementById('confirmPassword').value

        if (password == 0) {
            errorToast("password required");
        } else if (confirmPassword == 0) {
            errorToast("confirm password required");
        }else if (password != confirmPassword) {
            errorToast("Password Not Match");
        }else{
            showLoader();
            let res= await axios.post("/user_reset_password",{
                password:password
            });
            hideLoader();
            if(res.data['status']=="success"){
                successToast(res.data['message']);
                setTimeout(()=>{
                    window.location.href="/login"
                },1000);
            }else{
                errorToast(res.data['message']);
            }

        }

    }
</script>
