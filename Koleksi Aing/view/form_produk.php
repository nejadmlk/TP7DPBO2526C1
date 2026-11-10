
<?php
$isEdit = $halaman === 'edit_produk';
$item = $isEdit ? $editProduk : [];

if ($isEdit && !$item) {
    echo '<div class="alert error">Produk tidak ditemukan.</div>';
    return;
}
?>

<div class="panel form-panel">

    <h2><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h2>

    <form method="POST" enctype="multipart/form-data">

        <!-- Menentukan aksi -->
        <input type="hidden" name="aksi"
            value="<?= $isEdit ? 'ubah_produk' : 'tambah_produk' ?>">

        <?php if ($isEdit): ?>

            <input type="hidden" name="id_produk"
                value="<?= (int)$item['id_produk'] ?>">

            <input type="hidden" name="gambar_lama"
                value="<?= e($item['gambar']) ?>">

        <?php endif; ?>

        <div class="form-grid">

            <!-- Nama Produk -->
            <label>Nama Produk *
                <input required name="nama_produk"
                    value="<?= e($item['nama_produk'] ?? '') ?>"
                    placeholder="Contoh: S.H.Figuarts Son Goku">
            </label>

            <!-- Nama Karakter -->
            <label>Nama Karakter *
                <input required name="karakter"
                    value="<?= e($item['karakter'] ?? '') ?>"
                    placeholder="Contoh: Son Goku">
            </label>


            <!-- Pilihan Brand -->
            <label>Brand *
                <select name="pilihan_brand" id="pilihan_brand" required
                    onchange="tampilkanInputBrand()">

                    <option value="">Pilih Brand</option>

                    <?php foreach ($daftarBrand as $b): ?>

                        <option value="<?= (int)$b['id_brand'] ?>"
                            <?= (int)($item['id_brand'] ?? 0) === (int)$b['id_brand'] ? 'selected' : '' ?>>

                            <?= e($b['nama_brand']) ?>

                        </option>

                    <?php endforeach; ?>

                    <option value="baru">+ Tambah Brand Baru</option>

                </select>
            </label>


            <!-- Pilihan Kategori -->
            <label>Kategori *
                <select name="pilihan_kategori" id="pilihan_kategori" required
                    onchange="tampilkanInputKategori()">

                    <option value="">Pilih Kategori</option>

                    <?php foreach ($daftarKategori as $k): ?>

                        <option value="<?= (int)$k['id_kategori'] ?>"
                            <?= (int)($item['id_kategori'] ?? 0) === (int)$k['id_kategori'] ? 'selected' : '' ?>>

                            <?= e($k['nama_kategori']) ?>

                        </option>

                    <?php endforeach; ?>

                    <option value="baru">+ Tambah Kategori Baru</option>

                </select>
            </label>


            <!-- Input Brand Baru -->
            <div class="input-baru full" id="input_brand_baru"
                style="display: none;">

                <h3>Tambah Brand Baru</h3>

                <label>Nama Brand *
                    <input type="text" name="nama_brand_baru"
                        placeholder="Contoh: Bandai">
                </label>

                <label>Negara Asal *
                    <input type="text" name="negara_asal_baru"
                        placeholder="Contoh: Jepang">
                </label>

                <label>Deskripsi Brand
                    <textarea name="deskripsi_brand_baru" rows="3"></textarea>
                </label>

            </div>


            <!-- Input Kategori Baru -->
            <div class="input-baru full" id="input_kategori_baru"
                style="display: none;">

                <h3>Tambah Kategori Baru</h3>

                <label>Nama Kategori *
                    <input type="text" name="nama_kategori_baru"
                        placeholder="Contoh: Anime Figure">
                </label>

                <label>Deskripsi Kategori
                    <textarea name="deskripsi_kategori_baru" rows="3"></textarea>
                </label>

            </div>


            <!-- Harga Pembelian -->
            <label>Harga Pembelian (Rp) *
                <input required type="number" min="0" step="0.01"
                    name="harga_beli"
                    value="<?= e($item['harga_beli'] ?? '0') ?>">
            </label>


            <!-- Jumlah -->
            <label>Jumlah Dimiliki *
                <input required type="number" min="0"
                    name="jumlah_dimiliki"
                    value="<?= e($item['jumlah_dimiliki'] ?? '1') ?>">
            </label>


            <!-- Skala -->
            <label>Skala
                <input name="skala"
                    value="<?= e($item['skala'] ?? '') ?>"
                    placeholder="Contoh: 1:12">
            </label>


            <!-- Kondisi -->
            <label>Kondisi
                <select name="kondisi">

                    <?php foreach (['Baru', 'Bekas - baik', 'Bekas - cukup', 'Rusak'] as $kondisi): ?>

                        <option value="<?= e($kondisi) ?>"
                            <?= ($item['kondisi'] ?? 'Baru') === $kondisi ? 'selected' : '' ?>>

                            <?= e($kondisi) ?>

                        </option>

                    <?php endforeach; ?>

                </select>
            </label>


            <!-- Tanggal -->
            <label>Tanggal Diperoleh
                <input type="date" name="tanggal_diperoleh"
                    value="<?= e($item['tanggal_diperoleh'] ?? '') ?>">
            </label>


            <!-- Gambar -->
            <label>Gambar Produk
                <input type="file" name="gambar"
                    accept=".jpg,.jpeg,.png,.webp">

                <small>Format JPG, JPEG, WEBP. Maksimal 3 MB.</small>
            </label>


            <!-- Deskripsi -->
            <label class="full">Deskripsi Produk
                <textarea name="deskripsi" rows="4"><?= e($item['deskripsi'] ?? '') ?></textarea>
            </label>

        </div>


        <!-- Gambar lama -->
        <?php if (!empty($item['gambar'])): ?>

            <p>
                Gambar saat ini:
                <img class="current-image"
                    src="uploads/<?= e($item['gambar']) ?>" alt="">
            </p>

        <?php endif; ?>


        <!-- Tombol -->
        <div class="form-actions">

            <a class="button secondary"
                href="index.php?halaman=produk">
                Batal
            </a>

            <button class="button primary" type="submit">
                <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Koleksi' ?>
            </button>

        </div>

    </form>

</div>


<!-- JavaScript -->
<script>

function tampilkanInputBrand() {

    const pilihan = document.getElementById('pilihan_brand').value;
    const input = document.getElementById('input_brand_baru');

    const nama = input.querySelector('[name="nama_brand_baru"]');
    const negara = input.querySelector('[name="negara_asal_baru"]');

    input.style.display = pilihan === 'baru' ? 'block' : 'none';

    nama.required = pilihan === 'baru';
    negara.required = pilihan === 'baru';

}


function tampilkanInputKategori() {

    const pilihan = document.getElementById('pilihan_kategori').value;
    const input = document.getElementById('input_kategori_baru');

    const nama = input.querySelector('[name="nama_kategori_baru"]');

    input.style.display = pilihan === 'baru' ? 'block' : 'none';

    nama.required = pilihan === 'baru';

}

</script>
