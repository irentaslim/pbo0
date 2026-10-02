<?php
interface Pembayaran{ //membuat interface pembayaran
    public function bayar($jumlah); //method bayar
}

class GoPay implements Pembayaran{ //kelas GoPay mengimplementasikan interface pembayaran
    public function bayar($jumlah){ //mengimplementasikan method bayar
        return "Bayar dengan GoPay sebesar Rp".$jumlah;
    }
}

class QRIS implements Pembayaran{ //kelas QRIS mengimplementasikan interface pembayaran
    public function bayar($jumlah){ //mengimplementasikan method bayar
        return "Bayar dengan QRIS sebesar Rp".$jumlah;
    }
} 

class TransferBank implements Pembayaran{ //kelas TransferBank mengimplementasikan interface pembayaran
    public function bayar($jumlah){ //mengimplementasikan method bayar
        return "Bayar dengan Transfer Bank sebesar Rp".$jumlah;
    }
}

function prosesbayar (Pembayaran $penbayaran, $jumlah){ //poliomorfisme, fungsi prosesbayar menerima parameter objek pembayaran dan jumlah
    //fungsi prosesbayar menerima parameter objek pembayaran dan jumlah
    echo $penbayaran->bayar($jumlah); //memanggil method bayar dari objek pembayaran
}   

$goPay = new GoPay();
$qris = new QRIS();
$transferBank = new TransferBank();

prosesbayar($goPay, 10000);
echo "<br>";
prosesbayar($qris, 20000);
echo "<br>";
prosesbayar($transferBank, 30000);



?>