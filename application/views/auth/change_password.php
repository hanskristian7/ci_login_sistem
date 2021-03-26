<div class="container">

	<!-- Outer Row -->
	<div class="row justify-content-center">

		<div class="col-lg-7">

			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0">
					<!-- Nested Row within Card Body -->
					<div class="row">
						<!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> -->
						<div class="col-lg">
							<div class="p-5">
								<div class="text-center">
									<h1 class="h4 text-gray-900 mb-4">Change your password for <b><?= $this->session->userdata('reset_email'); ?></b></h1>
									<?= $this->session->flashdata('message'); ?>
								</div>
								<form action="<?= base_url('auth/changePassword'); ?>" method="POST" class="user">
									<div class="form-group">
										<input type="password" class="form-control form-control-user" name="newPassword" id="newPassword" placeholder="Enter new password">
										<?= form_error('newPassword', '<small class="text-danger pl-3">', '</small>'); ?>
									</div>
									<div class="form-group">
										<input type="password" class="form-control form-control-user" name="repeatNewPassword" id="repeatNewPassword" placeholder="Confirm new password">
										<?= form_error('repeatNewPassword', '<small class="text-danger pl-3">', '</small>'); ?>
									</div>

									<button type="submit" class="btn btn-primary btn-user btn-block">
										Reset Password
									</button>
								</form>
								<hr>
								<div class="text-center">
									<a class="small" href="<?= base_url('auth'); ?>">Back to login</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>

</div>
