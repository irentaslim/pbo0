<?php
//soal no 2: Fungsi Nilai Akhir
function hitungNilaiAkhir($uts,$uas,$tugas) {
    return ($uts*0.3) + ($uas*0.4) + ($tugas*0.3);
}
function Penilaian($nilai) {
   if ($nilai>=80) return "A"; 
   elseif ($nilai>= 70) return "B";
   elseif ($nilai>= 60) return "C";
   elseif ($nilai>= 50) return "D";
   else return "E";
}

$nilaiakhir=hitungNilaiAkhir(88,97,95);
echo "Nilai Akhir: $nilaiakhir <br>";
$grade=Penilaian($nilaiakhir);
echo "Grade : $grade";

?>
