<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 text-end">
                <button class="close-btn" onclick="closeSidebar()">×</button>
            </div>
            <div id="registerSection" class="col-12">
            <form id="registrationForm" class="col-12" method="post" action="../sql/register.php" enctype="multipart/form-data" autocomplete="off">
                <div class="wizardformstep1">
                <div class="col-12 text-center">
                    <h1>Register</h1>
                </div>
                <div class="text-center loginredirection mb-3 px-1">
                    <span>Already a member? <a href="#" id="showLoginForm">Login now</a></span>
                </div>
                
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="registration-input form-control" id="fullname" name="fullname" placeholder="e.g. Muhammad Alif" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="ic" class="form-label">IC</label>
                            <input type="number" class="registration-input form-control" id="ic" name="ic" required>
                        </div>
                        <div class="col">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select registration-input" id="gender" name="gender" required>
                                <option value="" selected disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>  
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="race" class="form-label">Race</label>
                            <input type="text" class="registration-input form-control" id="race" name="race" required>
                        </div>
                        <div class="col-6">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="registration-input form-control" id="religion" name="religion" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="birthdate" class="form-label">Birth Date</label>
                            <input type="date" class="registration-input form-control" id="birthdate" name="birthdate" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="birthplace" class="form-label">Birth Place</label>
                            <input type="text" class="registration-input form-control" id="birthplace" name="birthplace" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="homeaddress" class="form-label">Home Address</label>
                        <input type="text" class="registration-input form-control" id="homeaddress" name="homeaddress" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="phonenumber" class="form-label">Phone Number</label>
                            <input type="tel" class="registration-input form-control" id="phonenumber" name="phonenumber" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md">
                        <label for="status" class="form-label">Status</label>
                            <select class="form-select registration-input" id="userstatus" name="userstatus" required>
                                <option value="" selected disabled>Select Status</option>
                                <option value="MFLS Alumni">MFLS Alumni</option>
                                <option value="Associate Member">Associate Member (Not Alumni)</option>  
                            </select>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label for="proof" class="form-label">Certification of Participation</label>
                        <input type="file" class="form-control" id="proof" name="proof" accept=".pdf" required>
                    </div>

                    <div class="d-flex justify-content-center py-3">
                        <button type="submit" class="registerbtn" id="registrationstep2form">Next</button>
                    </div>
                    </div>

                    <div class="wizardformstep2">
                    <div class="col-12 text-center">
                        <h1>Register</h1>
                    </div>
                    <div class="text-center loginredirection mb-3 px-1">
                        <span>Already a member? <a href="#" id="showLoginForm">Login now</a></span>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                        <a id="redirectstep1" href="#">
                            <div class="redirectbackbtn-wrapper">
                                <div class="circle">
                                    <img src="../img/back.png">
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="registration-input form-control" id="username" name="username" required>
                        </div>

                        <div class="col-md">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="registration-input form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="registration-input form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md">
                        <label for="confirmpassword" class="form-label">Confirm Password</label>
                        <input type="password" class="registration-input form-control" id="confirmpassword" name="confirmpassword" required>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="agreement" name="agreement" required>
                        <label class="form-check-label" for="agreement">
                            I agree and confirm the provided information is true and accurate. I've also read the terms and conditions which were specified.
                        </label>
                    </div>
                    <div class="d-flex justify-content-center py-3">
                        <button type="submit" class="registerbtn" id="registerButton">Register now</button>
                    </div>

                    </div>
                </form>
            </div>
            <div id="loginSection" class="col-12" style="display: none;">
                <div class="col-12 text-center">
                    <h1>Login</h1>
                </div>
                <div class="text-center mb-3 loginredirection">
                    <span>Not yet a member? <a href="#" id="showRegisterForm">Register now</a></span>
                </div>
                <form id="loginForm" class="col-12" autocomplete="off">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email Address</label>
                        <input type="email" class="login-input form-control" id="loginEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="login-input form-control" id="loginPassword" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="d-flex justify-content-center py-3">
                        <button type="submit" class="loginbtn">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
