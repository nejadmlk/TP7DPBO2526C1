<div class="section-heading">
    <div><h2>Semua Koleksi</h2><p>Kelola informasi action figure yang kamu miliki.</p></div>
    <a class="button primary" href="index.php?halaman=tambah_produk">+ Tambah Produk</a>
</div>
<div class="panel table-wrap">
<table>
    <thead><tr><th>Produk</th><th>Brand</th><th>Kategori</th><th>Jumlah</th><th>Harga Beli</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($daftarProduk as $item): ?>
        <tr>
            <td><div class="table-product">
                <?php if ($item['gambar']): ?><img src="uploads/<?= e($item['gambar']) ?>" alt="">
                <?php else: ?><div class="mini-placeholder">✦</div><?php endif; ?>
                <div><b><?= e($item['nama_produk']) ?></b><small><?= e($item['karakter']) ?></small></div>
            </div></td>
            <td><?= e($item['nama_brand']) ?></td>
            <td><span class="tag"><?= e($item['nama_kategori']) ?></span></td>
            <td><?= (int)$item['jumlah_dimiliki'] ?></td>
            <td>Rp <?= number_format((float)$item['harga_beli'], 0, ',', '.') ?></td>
            <td class="actions">
                <a href="index.php?halaman=detail_produk&id=<?= (int)$item['id_produk'] ?>">Detail</a>
                <a href="index.php?halaman=edit_produk&id=<?= (int)$item['id_produk'] ?>">Edit</a>
                <form method="POST" onsubmit="return confirm('Hapus produk ini?')">
                    <input type="hidden" name="aksi" value="hapus_produk">
                    <input type="hidden" name="id_produk" value="<?= (int)$item['id_produk'] ?>">
                    <button class="link-danger" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$daftarProduk): ?><tr><td colspan="6" class="empty">Belum ada produk.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>
