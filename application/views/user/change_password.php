<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

	<div class="row">
		<div class="col-lg-6">

			<?= $this->session->flashdata('message'); ?>

			<form action="<?= base_url('user/changePassword'); ?>" method="POST">
				<div class="form-group">
					<label for="password1">Current Password</label>
					<input type="password" class="form-control" name="currentPassword" id="currentPassword">
					<?= form_error('currentPassword', '<small class="text-danger pl-3">', '</small>'); ?>
				</div>

				<div class="form-group">
					<label for="newPassword">New Password</label>
					<input type="password" class="form-control" name="newPassword" id="newPassword">
					<?= form_error('newPassword', '<small class="text-danger pl-3">', '</small>'); ?>
				</div>

				<div class="form-group">
					<label for="repeatNewPassword">Repeat New Password</label>
					<input type="password" class="form-control" name="repeatNewPassword" id="repeatNewPassword">
					<?= form_error('repeatNewPassword', '<small class="text-danger pl-3">', '</small>'); ?>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-danger">Change Password</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
