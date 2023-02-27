
<main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-7" style="background-color: #f5f5f5;">
                    <img src="<?php echo ASSET_PATH; ?>auth/images/pages/graphic-3.png" alt="login" class="login-card-img">
                </div>
                <div class="col-md-5">
                    <div class="card-body pt-0 pb-3">
                        <div class="py-4 text-center mb-3">
                            <img src="<?php echo BE_IMG_PATH; ?>logo.png" alt="logo" class="logo" height="100px">
                        </div>
                        <div class="card-title">
                            <p class="mb-0"><i class="fa fa-lock"></i> Login</p>
                        </div>
                        <p class="login-card-description">Welcome back, please login to your account.</p>
                        <form class="validate-form login-form" method="post" action="<?php echo base_url('auth/validate'); ?>">

                            <div class="alert alert-danger error-validate" role="alert" style="line-height: 20px; text-align: center; display: none;">
                                <i class="mdi mdi-information-outline"></i>
                                <span style="font-size:12px;"> Ada beberapa kesalahan, silahkan cek formulir di bawah !</span>
                            </div>

                            <div class="form-group has-icon-left">
                                <label for="username" class="sr-only">Username</label>
                                <div class="input-group">
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                                    <div class="form-control-icon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-icon-left mb-4">
                                <label for="password" class="sr-only">Password</label>
                                <div class="input-group show-hide-password">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                    <div class="input-group-append" id="button-eye">
                                        <button class="btn btn-primary" type="button">
                                            <i class="icon-eye fa fa-eye-slash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="form-control-icon">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" name="login" id="login" class="btn btn-block login-btn mb-4" type="button" value="Login">
                        </form>
                        <a href="#!" class="forgot-password-link">Forgot password?</a>
                        <p class="login-card-footer-text mb-5">Don't have an account? <a href="<?php echo base_url('register/agent'); ?>" class="text-reset">Register here</a></p>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo base_url(); ?>" class=""><i class="fa fa-home"></i> Home</a>
                            </div>
                            <div class="col-md-6 text-right">
                                <nav class="login-card-footer-nav">
                                    <a href="<?php echo base_url(); ?>"><?php echo COMPANY_NAME; ?> &copy; 2020.</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="card login-card">
        <img src="assets/images/login.jpg" alt="login" class="login-card-img">
        <div class="card-body">
        <h2 class="login-card-title">Login</h2>
        <p class="login-card-description">Sign in to your account to continue.</p>
        <form action="#!">
        <div class="form-group">
        <label for="email" class="sr-only">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-prompt-wrapper">
        <div class="custom-control custom-checkbox login-card-check-box">
        <input type="checkbox" class="custom-control-input" id="customCheck1">
        <label class="custom-control-label" for="customCheck1">Remember me</label>
        </div>              
        <a href="#!" class="text-reset">Forgot password?</a>
        </div>
        <input name="login" id="login" class="btn btn-block login-btn mb-4" type="button" value="Login">
        </form>
        <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
        </div>
        </div> -->
    </div>
</main>