<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="asset/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="asset/css/login.css">
	
		<title>Login</title>
	</head>
	
	<body>
		<div class="limiter">
			<div class="container-login">
				<div class="card-login">
					<span class="login-form-title">Login</span>
					
					<?= ($this->session->flashdata('pesan')) ? $this->session->flashdata('pesan') : '' ?>
					
					<form method="post" class="login-form validate-form flex-sb flex-w">
						<input type="hidden" name="redirect_to" value="<?= $redirect_to ?>">
						
						<label class="label-input">Username</label>
						<div class="wrap-input validate-input mb-3" data-validate = "Username is required">
							<input class="input" type="text" name="username" >
							<span class="focus-input"></span>
						</div>
						
						<label class="label-input">Password</label>
						<div class="wrap-input validate-input mb-2" data-validate = "Password is required">
							<span class="btn-show-pass">
								<i class="fa fa-eye"></i>
							</span>
							<input class="input" type="password" name="password">
							<span class="focus-input"></span>
						</div>
						
						<div class="row form-inline">
							<div class="col-md">
								<div class="contact-form-checkbox">
									<input class="input-checkbox" id="ckb1" type="checkbox" name="remember-me">
									<label class="label-checkbox" for="ckb1">
										Remember me
									</label>
								</div>
							</div>
	
							<div class="col-md text-right">
								<a href="#" class="forgot_pass">
									Forgot Password?
								</a>
							</div>
						</div>
	
						<div class="container-login-form-btn">
							<button class="login-form-btn">
								Login
							</button>
						</div>
	
					</form>
				</div>
			</div>
		</div>
		
		<script src="asset/js/jquery.js"></script>
		<script src="asset/js/bootstrap.bundle.min.js"></script>
		<script src="asset/js/view/login.js"></script>
	</body>
</html>
