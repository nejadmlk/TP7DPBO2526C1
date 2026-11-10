<?php if (!$editProduk): ?>
    <div class="alert error">Produk tidak ditemukan.</div>
<?php else: ?>
<div class="panel detail-layout">
    <?php if ($editProduk['gambar']): ?><img class="detail-image" src="uploads/<?= e($editProduk['gambar']) ?>" alt="<?= e($editProduk['nama_produk']) ?>">
    <?php else: ?><div class="detail-placeholder">✦</div><?php endif; ?>
    <div class="detail-content">
        <span class="tag"><?= e($editProduk['nama_kategori']) ?></span>
        <h2><?= e($editProduk['nama_produk']) ?></h2>
        <p class="muted"><?= e($editProduk['nama_brand']) ?> · <?= e($editProduk['karakter']) ?></p>
        <h3>Rp <?= number_format((float)$editProduk['harga_beli'], 0, ',', '.') ?></h3>
        <div class="detail-list">
            <p><b>Jumlah dimiliki:</b> <?= (int)$editProduk['jumlah_dimiliki'] ?> unit</p>
            <p><b>Skala:</b> <?= e($editProduk['skala'] ?: '-') ?></p>
            <p><b>Kondisi:</b> <?= e($editProduk['kondisi']) ?></p>
            <p><b>Tanggal diperoleh:</b> <?= e($editProduk['tanggal_diperoleh'] ?: '-') ?></p>
        </div>
        <p><?= nl2br(e($editProduk['deskripsi'])) ?></p>
        <a class="button primary" href="index.php?halaman=edit_produk&id=<?= (int)$editProduk['id_produk'] ?>">Edit Produk</a>
        <a class="button secondary" href="index.php?halaman=produk">Kembali</a>
    </div>
</div>
<?php endif; ?>
