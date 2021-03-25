<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

	<div class="row">
		<div class="col-lg-9">
			<!-- kalau eror -->
			<?php if (validation_errors()) : ?>
				<div class="alert alert-danger" role="alert">
					<?= validation_errors();  ?>
				</div>
			<?php endif; ?>

			<!-- kalau berhasil -->
			<?= $this->session->flashdata('message'); ?>

			<a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSubMenuModal">Add new submenu</a>

			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Menu ID</th>
						<th scope="col">Title</th>
						<th scope="col">Url</th>
						<th scope="col">Icon</th>
						<th scope="col">Is_active</th>
						<th scope="col">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($subMenu as $sm) : ?>
						<tr>
							<th scope="row"><?= $i; ?></th>
							<td><?= $sm['menu']; ?></td>
							<td><?= $sm['title']; ?></td>
							<td><?= $sm['url']; ?></td>
							<td><?= $sm['icon']; ?></td>
							<td><?= $sm['is_active']; ?></td>
							<td>
								<a href="<?= base_url('menu/editSubmenu/' . $sm['id']); ?>" class="badge badge-success">edit</a>
								<a href="<?= base_url('menu/deleteSubmenu/' . $sm['id']); ?>" class="badge badge-danger" onclick="return confirm('Are you sure to delete this data?')">delete</a>
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

<!-- New sub menu modal -->
<div class="modal fade" id="newSubMenuModal" tabindex="-1" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newSubMenuModal">New sub menu</h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="<?= base_url('menu/submenu'); ?>" method="POST">
				<div class="modal-body">
					<div class="form-group">
						<select name="menu_id" id="menu_id" class="form-control">
							<option value="">-Select menu-</option>
							<?php foreach ($menu as $m) : ?>
								<option value="<?= $m['id'] ?>"><?= $m['menu']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<input type="text" class="form-control" id="title" name="title" placeholder="Submenu title">
					</div>

					<div class="form-group">
						<input type="text" class="form-control" id="url" name="url" placeholder="URL">
					</div>

					<div class="form-group">
						<input type="text" class="form-control" id="icon" name="icon" placeholder="Icon">
					</div>

					<div class="form-group">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" value="1" name="is_active" id="is_active" checked>
							<label for="is_active" class="form-check-label">Active?</label>
						</div>
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
<!-- end add submenu modal -->
