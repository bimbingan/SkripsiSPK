<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Tabel Siswa</h3>

			</div><!-- /.box-header -->
			<div class="box-body no-padding">
				<table class="table">
					<tbody>
						<tr>
							<th style="width: 10px">No</th>
							<th>Nis</th>
							<th>Nama</th>
							<th>Aksi</th>
						</tr>
						<?php foreach ($siswa as $key => $s): ?>
							<tr>
								<td><?php echo ++$key; ?></td>
								<td><?php echo $s['nis']; ?></td>
								<td><?php echo $s['nama']; ?></td>
								<td>
									<a class="btn btn-primary" href="<?php echo site_url().'siswa/edit/'.$s['nis']; ?>">Edit</a> |
									<a class="btn btn-danger" href="<?php echo site_url().'/master/siswa/hapus/'.$s['nis']; ?>">Hapus</a>
								</td>
							</tr>
						<?php endforeach; ?>

					</tbody>
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
</div>

<form action="<?php echo base_url('content/siswa'); ?>" method="post">
	<div class="form-group">
		<label for="nis_siswa">Nis</label>
		<input class="form-control" name="nis_siswa" placeholder="Nis Siswa" />
	</div>
	<div class="form-group">
		<label for="nama_siswa">Nama</label>
		<input class="form-control" name="nama_siswa" placeholder="Nama Siswa" />
	</div>
	<div class="form-group">
		<label for="jk_siswa">Jenis Kelamin</label>
		<input class="form-control" name="nis_siswa" placeholder="Jenis Kelamin" />
	</div>
	<div class="form-group">
		<label for="nis_siswa">Tempat Tanggal Lahir</label>
		<input class="form-control" name="ttl_siswa" placeholder="Tempat Tanggal Lahir" />
	</div>
	<div class="form-group">
		<label for="minat_siswa">Minat 1</label>
		<input class="form-control" name="minat_siswa" placeholder="Minat 1" />
	</div>
	<div class="form-group">
		<label for="minat_siswa">Minat 2</label>
		<input class="form-control" name="minat_siswa" placeholder="Minat 2" />
	</div>
	<div class="form-group">
		<label for="asal_siswa">Asal Sekolah</label>
		<input class="form-control" name="asal_siswa" placeholder="Asal Sekolah" />
	</div>
	<button type="submit" class="btn btn-default">Submit</button>

</form>