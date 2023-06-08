<div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Form Tambah Produk</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col-lg-7 mb-5">
                <div class="contact-form">
                    <div id="success"></div>
                    <form action="<?php echo site_url('produk/save'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idToko" value="<?php echo $idToko; ?>">
                    <select name="kategori" id="" class="form-control">
                        <?php foreach ($kategori as $val) { ?>
                            <option value="<?php echo $val->idkat; ?>"><?php echo $val->namaKat; ?></option>
                        <?php } ?>
                    </select>
                    <br>
                        <div class="control-group">
                            <input type="text" class="form-control" id="name" name="namaProduk" placeholder="Nama Produk"
                                required="required" data-validation-required-message="Mohon isi nama Toko mu" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <input type="file" class="form-control" id="password" name="gambar" placeholder="Gambar Produk"
                                required="required" data-validation-required-message="Please enter your email" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <input type="text" name="hargaProduk" class="form-control" placeholder="Harga Produk" 
                            required="required" data-validation-required-massage="Masukkan Harga Produk" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <input type="text" name="jumlahProduk" class="form-control" placeholder="Jumlah Produk" 
                            required="required" data-validation-required-massage="Masukkan Jumlah Produk" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <input type="text" name="beratProduk" class="form-control" placeholder="Berat Produk" 
                            required="required" data-validation-required-massage="Masukkan Berat Produk" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <textarea name="deskripsi" id="message" cols="30" rows="3"
                            required="required" data-validation-required-message="Masukkan Deskripsi Toko"></textarea>
                        </div>
                        <div>
                            <button class="btn btn-primary py-2 px-4" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>