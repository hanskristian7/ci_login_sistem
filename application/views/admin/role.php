<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

	<div class="row">
		<div class="col-lg-6">
			<!-- kalau eror -->
			<?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

			<!-- kalau berhasil -->
			<?= $this->session->flashdata('message'); ?>

			<a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newRoleModal">Add new role</a>

			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Role</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($role as $r) : ?>
						<tr>
							<th scope="row"><?= $i; ?></th>
							<td><?= $r['role']; ?></td>
							<td>
								<a href="<?= base_url('admin/roleAccess/' . $r['id']); ?>" class="badge badge-warning">akses</a>
								<a href="<?= base_url('menu/edit/' . $r['id']); ?>" class="badge badge-success">edit</a>
								<a href="<?= base_url('menu/delete/' . $r['id']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure to delete this data?')">delete</a>
							</td>
						</tr>
						<?php $i++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- new menu modal -->
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newRoleModal">New Role</h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="<?= base_url('admin/role'); ?>" method="POST">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" id="menu" name="menu" placeholder="Role">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- end add menu modal -->
