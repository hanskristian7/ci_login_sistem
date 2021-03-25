<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

	<div class="row">
		<div class="col-lg-6">
			<form action="<?= base_url('menu/edit/' . $menu[0]['id']); ?>" method="POST">
				<div class="form-group">
					<input type="text" class="form-control" id="menu" name="menu" placeholder="Menu name..." value="<?= $menu[0]['menu']; ?>" autofocus>
					<input type="hidden" name="id" id="id" value="<?= $menu[0]['id']; ?>">
					<button type="submit" class="btn btn-success mt-3">Edit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
