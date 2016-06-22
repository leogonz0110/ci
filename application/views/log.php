<body class="sign-in">
<div id="wrapper">
	<div class="login-box">
		<div class="login-logo">
			<a href="../../index2.html"><b>Admin</b>LTE</a>
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<?php if($this->session->flashdata('message')) { ?>
			<div class="alert alert-danger fade in">
				<?php echo $this->session->flashdata('message')?>
			</div>
			<?php } else { ?>
				<p class="login-box-msg">Sign in to start your session</p>
			<?php } ?>

			<?php echo form_open(base_url('user/login'),['autocomplete'=> 'off'])?>
				<div class="form-group has-feedback">
					<?php
						$data = array(
								  'name'        => 'email',
								  'class'		=> 'form-control',
								  'placeholder'	=> 'Email',
								);
						echo form_input($data);
					?>
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<?php
						$data = array(
								  'name'        => 'password',
								  'class'		=> 'form-control',
								  'placeholder'	=> 'Password',
								);
						echo form_password($data);
					?>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
				</div>
				<!-- /.col -->

				<div class="col-xs-8">
					<div class="checkbox icheck pull-right">
						<label>
							<input type="checkbox"> Remember Me
						</label>
					</div>
				</div>
				<!-- /.col -->
				</div>
			<?php echo form_close()?>

			<a href="#">I forgot my password</a><br>
			<a href="register.html" class="text-center">Register a new membership</a>

		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->
</div>
