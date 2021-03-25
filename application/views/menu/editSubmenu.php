<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

	<div class="row">
		<div class="col-lg-6">
			<form action="<?= base_url('menu/editSubmenu/' . $subMenu[0]['id']); ?>" method="POST">
				<div class="form-group">
					<input type="hidden" name="id" id="id" value="<?= $subMenu[0]['id']; ?>">
					<select name="menu_id" id="menu_id" class="form-control">
						<option value="">-Select menu-</option>
						<?php foreach ($menu as $m) : ?>
							<option value="<?= $m['id']; ?>"> <?= $m['menu']; ?> </option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group">
					<input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?= $subMenu[0]['title']; ?>">
				</div>

				<div class="form-group">
					<input type="text" class="form-control" id="url" name="url" placeholder="URL" value="<?= $subMenu[0]['url']; ?>">
				</div>

				<div class="form-group">
					<input type="text" class="form-control" id="icon" name="icon" placeholder="Icon" value="<?= $subMenu[0]['icon']; ?>">
				</div>

				<div class="form-group">
					<div class="form-check">
						<?php if ($subMenu[0]['is_active'] == 1) : ?>
							<input type="checkbox" class="form-check-input" value="1" name="is_active" id="is_active" checked>
						<?php else : ?>
							<input type="checkbox" class="form-check-input" value="1" name="is_active" id="is_active">
						<?php endif; ?>
						<label for="is_active" class="form-check-label">Active?</label>
					</div>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-success mt-3">Edit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
