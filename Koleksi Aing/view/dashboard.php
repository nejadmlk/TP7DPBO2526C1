
<!-- Statistik Koleksi -->
<section class="panel table-wrap">
    <h2>Ringkasan Koleksi</h2>

    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Jumlah / Nilai</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Total Jenis Figure</td>
                <td><?= e($statistik['total_jenis']) ?> produk</td>
            </tr>

            <tr>
                <td>Total Unit Koleksi</td>
                <td><?= e($statistik['total_unit']) ?> unit</td>
            </tr>

            <tr>
                <td>Total Brand</td>
                <td><?= count($daftarBrand) ?> brand</td>
            </tr>

            <tr>
                <td>Nilai Pembelian</td>
                <td>
                    Rp <?= number_format(
                        (float)$statistik['total_nilai'],
                        0,
                        ',',
                        '.'
                    ) ?>
                </td>
            </tr>
        </tbody>
    </table>
</section>

<!-- Galeri Koleksi -->
<section class="collection-section">

    <div class="section-heading">
        <div>
            <h2>Koleksi Terbaru</h2>
            <p>Lihat action figure yang kamu miliki.</p>
        </div>

        <a class="button primary" href="index.php?halaman=produk">
            Lihat Semua Koleksi
        </a>
    </div>

    <div class="collection-grid">

        <?php foreach (array_slice($daftarProduk, 0, 4) as $item): ?>

            <article class="collection-card">

                
                <!-- Gambar Produk -->
                <div class="collection-image">

                    <?php
                        $namaGambar = $item['gambar'] ?? '';
                        $lokasiGambar = __DIR__ . '/../uploads/' . $namaGambar;
                    ?>

                    <?php if (!empty($namaGambar) && is_file($lokasiGambar)): ?>

                    <img
                        src="uploads/<?= rawurlencode(basename($namaGambar)) ?>"
                        alt="<?= e($item['nama_produk']) ?>"
                    >

                    <?php else: ?>

                    <div class="collection-placeholder">
                        Gambar tidak ditemukan
                    </div>

                    <?php endif; ?>

                </div>


                <!-- Informasi Produk -->
                <div class="collection-info">

                    <span class="tag">
                        <?= e($item['nama_kategori']) ?>
                    </span>

                    <h3>
                        <?= e($item['nama_produk']) ?>
                    </h3>

                    <p class="collection-brand">
                        <?= e($item['nama_brand']) ?>
                        ·
                        <?= e($item['karakter']) ?>
                    </p>

                    <div class="collection-footer">

                        <div>
                            <small>Harga Pembelian</small>

                            <strong>
                                Rp <?= number_format(
                                    (float)$item['harga_beli'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </strong>
                        </div>

                        <a
                            href="index.php?halaman=detail_produk&id=<?= (int)$item['id_produk'] ?>"
                            class="detail-link"
                        >
                            Detail →
                        </a>

                    </div>

                </div>

            </article>

        <?php endforeach; ?>

        <?php if (empty($daftarProduk)): ?>

            <div class="empty">
                Belum ada koleksi. Tambahkan action figure pertamamu.
            </div>

        <?php endif; ?>

    </div>

</section>
