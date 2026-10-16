
<?php
session_start();

require_once 'data/menu.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

function kembaliDenganPesan($pesan, $jenis)
{
    $_SESSION['flash'] = array(
        'pesan' => $pesan,
        'jenis' => $jenis
    );

    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';

if ($aksi === 'tambah') {
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';
    $inputJumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : '';

    if (!isset($menu[$kode])) {
        kembaliDenganPesan('Kode menu tidak valid.', 'error');
    }

    if (!is_string($inputJumlah) ||
        !preg_match('/^(?:[1-9]|10)$/', $inputJumlah)) {
        kembaliDenganPesan(
            'Jumlah harus bilangan bulat dari 1 sampai 10.',
            'error'
        );
    }

    $jumlah = (int) $inputJumlah;

    $jumlahLama = isset($_SESSION['keranjang'][$kode])
        ? $_SESSION['keranjang'][$kode] : 0;

    if ($jumlahLama + $jumlah > 10) {
        kembaliDenganPesan(
            'Jumlah maksimal 10. Keranjang tidak diubah.',
            'error'
        );
    }

    $_SESSION['keranjang'][$kode] = $jumlahLama + $jumlah;

    $_SESSION['flash'] = array(
        'pesan' => 'Pesanan berhasil ditambahkan.',
        'jenis' => 'success'
    );

    header('Location: keranjang.php');
    exit;
}

if ($aksi === 'hapus') {
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';

    if (isset($_SESSION['keranjang'][$kode])) {
        unset($_SESSION['keranjang'][$kode]);

        $_SESSION['flash'] = array(
            'pesan' => 'Menu berhasil dihapus.',
            'jenis' => 'success'
        );
    }

    header('Location: keranjang.php');
    exit;
}

if ($aksi === 'kosongkan') {
    $_SESSION['keranjang'] = array();

    $_SESSION['flash'] = array(
        'pesan' => 'Keranjang berhasil dikosongkan.',
        'jenis' => 'success'
    );

    header('Location: keranjang.php');
    exit;
}

kembaliDenganPesan('Aksi tidak dikenal.', 'error');
