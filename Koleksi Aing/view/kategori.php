<?php
$isEdit = $halaman === 'edit_kategori';
$item = $isEdit ? $editKategori : [];
?>
<div class="two-column">
    <section class="panel form-panel">
        <h2><?= $isEdit ? 'Edit Kategori' : 'Tambah Kategori' ?></h2>
        <?php if ($isEdit && !$item): ?>
            <div class="alert error">Kategori tidak ditemukan.</div>
        <?php else: ?>
        <form method="POST">
            <input type="hidden" name="aksi" value="<?= $isEdit ? 'ubah_kategori' : 'tambah_kategori' ?>">
            <?php if ($isEdit): ?><input type="hidden" name="id_kategori" value="<?= (int)$item['id_kategori'] ?>"><?php endif; ?>
            <label>Nama Kategori *
                <input required name="nama_kategori" value="<?= e($item['nama_kategori'] ?? '') ?>">
            </label>
            <label>Deskripsi
                <textarea name="deskripsi" rows="4"><?= e($item['deskripsi'] ?? '') ?></textarea>
            </label>
            <div class="form-actions">
                <?php if ($isEdit): ?><a class="button secondary" href="index.php?halaman=kategori">Batal</a><?php endif; ?>
                <button class="button primary" type="submit"><?= $isEdit ? 'Simpan' : 'Tambah Kategori' ?></button>
            </div>
        </form>
        <?php endif; ?>
    </section>
    <section class="panel table-wrap">
        <h2>Daftar Kategori</h2>
        <table>
            <thead><tr><th>Nama Kategori</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($daftarKategori as $k): ?>
                <tr><td><b><?= e($k['nama_kategori']) ?></b></td><td><?= e($k['deskripsi']) ?></td>
                    <td class="actions"><a href="index.php?halaman=edit_kategori&id=<?= (int)$k['id_kategori'] ?>">Edit</a>
                    <form method="POST" onsubmit="return confirm('Hapus kategori ini? Kategori yang dipakai produk tidak dapat dihapus.')">
                        <input type="hidden" name="aksi" value="hapus_kategori"><input type="hidden" name="id_kategori" value="<?= (int)$k['id_kategori'] ?>">
                        <button class="link-danger" type="submit">Hapus</button>
                    </form></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
