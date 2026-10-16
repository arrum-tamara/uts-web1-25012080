
<?php

function rupiah($angka)
{
    return 'Rp' . number_format($angka, 0, ',', '.');
}

function hitungSubtotal($harga, $jumlah)
{
    return $harga * $jumlah;
}

function hitungTotal($keranjang, $menu)
{
    $total = 0;

    foreach ($keranjang as $kode => $jumlah) {
        if (isset($menu[$kode])) {
            $total += hitungSubtotal(
                $menu[$kode]['harga'],
                $jumlah
            );
        }
    }

    return $total;
}

function hitungDiskon($subtotal)
{
    return $subtotal >= 50000
        ? $subtotal * 0.10
        : 0;
}
