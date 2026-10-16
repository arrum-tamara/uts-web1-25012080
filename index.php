
<?php
require_once 'data/menu.php';
require_once 'includes/fungsi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

$kataKunci = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$kategoriList = array();

foreach ($menu as $item) {
    if (!in_array($item['kategori'], $kategoriList)) {
        $kategoriList[] = $item['kategori'];
    }
}

$kategoriGET = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$kategoriCookie = isset($_COOKIE['kategori_favorit'])
    ? $_COOKIE['kategori_favorit'] : '';

if ($kategoriGET !== '') {
    $kategoriAktif = in_array($kategoriGET, $kategoriList, true)
        ? $kategoriGET : '';
} else {
    $kategoriAktif = in_array($kategoriCookie, $kategoriList, true)
        ? $kategoriCookie : '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['simpan_kategori'])) {

    $pilihan = isset($_POST['kategori']) ? $_POST['kategori'] : '';

    if ($pilihan === '' || in_array($pilihan, $kategoriList, true)) {
        setcookie(
            'kategori_favorit',
            $pilihan,
            time() + (7 * 24 * 60 * 60),
            '/'
        );

        header('Location: index.php');
        exit;
    }
}

require 'includes/header.php';

if (isset($_SESSION['flash'])) {
    echo '<p>' .
        htmlspecialchars($_SESSION['flash']['pesan'], ENT_QUOTES, 'UTF-8') .
        '</p>';
    unset($_SESSION['flash']);
}
?>

<h2>Daftar Menu Kantin</h2>

<form method="get" action="index.php">
    <label>Cari menu:</label>
    <input type="text" name="cari"
        value="<?php echo htmlspecialchars($kataKunci, ENT_QUOTES, 'UTF-8'); ?>"
        placeholder="Nama menu">
    <button type="submit">Cari</button>
</form>

<h3>Kategori Favorit</h3>

<form method="post" action="index.php">
    <select name="kategori">
        <option value="">Semua kategori</option>
        <?php foreach ($kategoriList as $kat) { ?>
            <option value="<?php echo htmlspecialchars($kat, ENT_QUOTES, 'UTF-8'); ?>"
                <?php if ($kategoriAktif === $kat) echo 'selected'; ?>>
                <?php echo htmlspecialchars($kat, ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit" name="simpan_kategori" value="1">
        Simpan Favorit
    </button>
</form>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Kode</th>
        <th>Nama Menu</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Pesan</th>
    </tr>

    <?php
    $jumlahHasil = 0;

    foreach ($menu as $item) {
        if ($kataKunci !== ''
            && stripos($item['nama'], $kataKunci) === false) {
            continue;
        }

        if ($kategoriAktif !== ''
            && $item['kategori'] !== $kategoriAktif) {
            continue;
        }

        $jumlahHasil++;
    ?>
        <tr>
            <td><?php echo htmlspecialchars($item['kode'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($item['kategori'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo rupiah($item['harga']); ?></td>
            <td>
                <form method="post" action="proses.php">
                    <input type="hidden" name="aksi" value="tambah">
                    <input type="hidden" name="kode"
                        value="<?php echo htmlspecialchars($item['kode'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="number" name="jumlah"
                        min="1" max="10" step="1" value="1" required>
                    <button type="submit">Tambah</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
if ($jumlahHasil === 0) {
    echo '<p>Menu tidak ditemukan.</p>';
}

require 'includes/footer.php';
?>
