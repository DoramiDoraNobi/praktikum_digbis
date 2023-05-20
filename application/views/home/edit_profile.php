<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Form Tambah Kategori</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Tambah Kategori</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
     <!-- Main content -->
     <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Data Kategori</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <form class="form-horizontal" method="post" action="<?php echo site_url('main/update_profile') ?>">
                <input type="hidden" name="id" value="<?php echo $member->idMember; ?>">
                <div class="card-body">
                <div class="form-group">
                    <input type="text" name="username" value="<?php echo $member->username; ?>" class="form-control" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="text" name="namaKonsumen" value="<?php echo $member->namaKonsumen; ?>" class="form-control" placeholder="Nama Konsumen">
                </div>
                <div class="form-group">
                    <input type="text" name="alamat" value="<?php echo $member->alamat; ?>" class="form-control" placeholder="Alamat">
                </div>
                <div class="form-group">
                    <input type="text" name="email" value="<?php echo $member->email; ?>" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="text" name="tlpn" value="<?php echo $member->tlpn; ?>" class="form-control" placeholder="Telepon">
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-info float-right">Submit</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
</section>
</div>