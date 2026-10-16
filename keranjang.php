
<?php
require_once 'data/menu.php';
require_once 'includes/fungsi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$keranjang = $_SESSION['keranjang'];
$subtotal = hitungTotal($keranjang, $menu);
$diskon = hitungDiskon($subtotal);
$totalAkhir = $subtotal - $diskon;

require 'includes/header.php';

if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    echo '<p>' .
        htmlspecialchars($flash['pesan'], ENT_QUOTES, 'UTF-8') .
        '</p>';
}
?>

<h2>Keranjang Pesanan</h2>

<?php if (empty($keranjang)): ?>
    <p>Keranjang masih kosong.</p>
    <p><a href="index.php">Kembali ke daftar menu</a></p>
<?php else: ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Nama Menu</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($keranjang as $kode => $jumlah): ?>
        <?php if (isset($menu[$kode])): ?>
        <tr>
            <td><?= htmlspecialchars($menu[$kode]['nama'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= rupiah($menu[$kode]['harga']) ?></td>
            <td><?= (int) $jumlah ?></td>
            <td><?= rupiah(hitungSubtotal($menu[$kode]['harga'], $jumlah)) ?></td>
            <td>
                <form method="post" action="proses.php">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="kode"
                           value="<?= htmlspecialchars($kode, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>

<p>Subtotal seluruh pesanan: <strong><?= rupiah($subtotal) ?></strong></p>
<p>Diskon: <strong><?= rupiah($diskon) ?></strong></p>
<p>Total pembayaran: <strong><?= rupiah($totalAkhir) ?></strong></p>

<form method="post" action="proses.php">
    <input type="hidden" name="aksi" value="kosongkan">
    <button type="submit">Kosongkan Keranjang</button>
</form>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>

<tr>
    <td colspan="3">Subtotal</td>
    <td>
        <?php echo rupiah($subtotal); ?>
    </td>
</tr>

<tr>
    <td colspan="3">Diskon (10%)</td>
    <td>
        <?php echo rupiah($diskon); ?>
    </td>
</tr>

<tr>
    <td colspan="3"><strong>Total Bayar</strong></td>
    <td>
        <strong><?php echo rupiah($totalAkhir); ?></strong>
    </td>
</tr>
