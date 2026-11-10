<?php
$isEdit = $halaman === 'edit_brand';
$item = $isEdit ? $editBrand : [];
?>
<div class="two-column">
    <section class="panel form-panel">
        <h2><?= $isEdit ? 'Edit Brand' : 'Tambah Brand' ?></h2>
        <?php if ($isEdit && !$item): ?>
            <div class="alert error">Brand tidak ditemukan.</div>
        <?php else: ?>
        <form method="POST">
            <input type="hidden" name="aksi" value="<?= $isEdit ? 'ubah_brand' : 'tambah_brand' ?>">
            <?php if ($isEdit): ?><input type="hidden" name="id_brand" value="<?= (int)$item['id_brand'] ?>"><?php endif; ?>
            <label>Nama Brand *
                <input required name="nama_brand" value="<?= e($item['nama_brand'] ?? '') ?>">
            </label>
            <label>Negara Asal *
                <input required name="negara_asal" value="<?= e($item['negara_asal'] ?? '') ?>">
            </label>
            <label>Deskripsi
                <textarea name="deskripsi" rows="4"><?= e($item['deskripsi'] ?? '') ?></textarea>
            </label>
            <div class="form-actions">
                <?php if ($isEdit): ?><a class="button secondary" href="index.php?halaman=brand">Batal</a><?php endif; ?>
                <button class="button primary" type="submit"><?= $isEdit ? 'Simpan' : 'Tambah Brand' ?></button>
            </div>
        </form>
        <?php endif; ?>
    </section>
    <section class="panel table-wrap">
        <h2>Daftar Brand</h2>
        <table>
            <thead><tr><th>Brand</th><th>Negara</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($daftarBrand as $b): ?>
                <tr><td><b><?= e($b['nama_brand']) ?></b></td><td><?= e($b['negara_asal']) ?></td>
                    <td class="actions"><a href="index.php?halaman=edit_brand&id=<?= (int)$b['id_brand'] ?>">Edit</a>
                    <form method="POST" onsubmit="return confirm('Hapus brand ini? Brand yang dipakai produk tidak dapat dihapus.')">
                        <input type="hidden" name="aksi" value="hapus_brand"><input type="hidden" name="id_brand" value="<?= (int)$b['id_brand'] ?>">
                        <button class="link-danger" type="submit">Hapus</button>
                    </form></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
