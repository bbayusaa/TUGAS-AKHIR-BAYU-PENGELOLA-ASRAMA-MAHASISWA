<?php
// --- KODE UTILITY DAN DATA PHP (Sama) ---
function read_json($path) {
    if (!file_exists($path)) {
        if (!is_dir(__DIR__ . "/data")) {
            mkdir(__DIR__ . "/data", 0777, true);
        }
        return [];
    }
    $raw = file_get_contents($path);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

$queue_path = __DIR__ . "/data/queue.json";
$aktif_path = __DIR__ . "/data/aktif.json";
$riwayat_path = __DIR__ . "/data/riwayat.json";

$queue = read_json($queue_path);
$aktif = read_json($aktif_path);
$riwayat = read_json($riwayat_path);

// Logika Kapasitas Terpakai
$kapasitas = [1 => ['Single' => 3, 'Double' => 2], 2 => ['Single' => 3, 'Double' => 2], 3 => ['Single' => 3, 'Double' => 2],];
$terpakai = [1 => ['Single' => 0, 'Double' => 0], 2 => ['Single' => 0, 'Double' => 0], 3 => ['Single' => 0, 'Double' => 0],];
foreach ($aktif as $p) {
    $lantai = (int)($p['lantai'] ?? 0);
    $tipe = $p['kamar'] ?? '';
    if (isset($terpakai[$lantai][$tipe])) {
        $terpakai[$lantai][$tipe]++;
    }
}

// Fungsi pembantu untuk memformat tanggal ke D-M-Y
function format_tanggal_indonesia($date_string) {
    if (empty($date_string) || $date_string === 'N/A') return 'N/A';
    if (strtotime($date_string)) {
        return date('d-m-Y', strtotime($date_string));
    }
    return $date_string;
}

// --- LOGIKA ROUTING ---
$page_map = [
    'home'      => 'content/home.php', 
    'form'      => 'content/form.php',
    'kapasitas' => 'content/kapasitas.php',
    'antrian'   => 'content/antrian.php',
    'aktif'     => 'content/aktif.php',
    'riwayat'   => 'content/riwayat.php',
];
$current_page = $_GET['page'] ?? 'home';
$content_file = $page_map[$current_page] ?? 'content/home.php'; 

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Asrama Mahasiswa - <?php echo ucfirst($current_page); ?></title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <header class="hero">
        <div class="hero-content">
            <h1>🏠 Asrama Mahasiswa UNDIP</h1>
            <p>Kelola penghuni, kapasitas kamar, dan riwayat dengan mudah</p>
        </div>
    </header>

    <nav class="navbar">
        <ul>
            <li><a href="?page=form">➕ Tambah Penghuni</a></li>
            <li><a href="?page=kapasitas">📊 Kamar Tersedia</a></li>
            <li><a href="?page=antrian">📥 Antrian (<?php echo count($queue); ?>)</a></li>
            <li><a href="?page=aktif">👥 Penghuni Aktif (<?php echo count($aktif); ?>)</a></li>
            <li><a href="?page=riwayat">📜 Riwayat</a></li>
        </ul>
    </nav>

    <main class="grid-page">
        <?php 
            // MEMUAT KONTEN SESUAI DENGAN PARAMETER URL (?page=...)
            if (file_exists($content_file)) {
                include $content_file;
            } else {
                echo "<section class='card'><h2>Halaman Tidak Ditemukan</h2><p>Konten untuk halaman ini tidak tersedia.</p></section>";
            }
        ?>
    </main>

    <footer class="footer">
        <form action="proses.php" method="post">
            <input type="hidden" name="aksi" value="reset" />
            <button type="submit" class="btn secondary">🔄 Reset Semua Data</button>
        </form>
        <p class="footnote">Bayu Seno Aji</p>
    </footer>

<script>
    // Fungsi form dinamis (harus dijalankan hanya jika elemen ada)
    const kamarSelect = document.getElementById('kamarSelect');
    const namaFields = document.getElementById('namaFields');

    function updateNamaFields() {
        if (!kamarSelect || !namaFields) return;

        const value = kamarSelect.value;
        let htmlContent = '';
        
        if (value === 'Single') {
            htmlContent = `
                <label for="nama1">Nama Penghuni
                    <input type="text" id="nama1" name="nama1" required />
                </label>
                <label for="no_wa1">📞 No. WhatsApp
                    <input type="tel" id="no_wa1" name="no_wa1" placeholder="Contoh: 081234567890" required />
                </label>
            `;
        } else if (value === 'Double') {
            htmlContent = `
                <label for="nama1">Nama Penghuni 1
                    <input type="text" id="nama1" name="nama1" required />
                </label>
                <label for="no_wa1">📞 No. WhatsApp 1
                    <input type="tel" id="no_wa1" name="no_wa1" placeholder="Contoh: 081234567890" required />
                </label>
                <label for="nama2">Nama Penghuni 2
                    <input type="text" id="nama2" name="nama2" required />
                </label>
                <label for="no_wa2">📞 No. WhatsApp 2
                    <input type="tel" id="no_wa2" name="no_wa2" placeholder="Contoh: 081234567890" required />
                </label>
            `;
        }
        namaFields.innerHTML = htmlContent;
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateNamaFields();
    });
    if (kamarSelect) {
        kamarSelect.addEventListener('change', updateNamaFields);
    }

    // Fungsi filterAktif (diperbarui untuk bekerja dengan TABLE)
    function filterAktif() {
        const lantaiFilter = document.getElementById('filterLantai')?.value;
        const kamarFilter = document.getElementById('filterKamar')?.value;
        const listAktif = document.getElementById('listAktif'); // Ini sekarang adalah <tbody>
        const filterEmptyMessage = document.getElementById('filterEmptyMessage');

        if (!listAktif) return;

        // Mengambil baris tabel (<tr>)
        const listItems = listAktif.querySelectorAll('tr'); 
        let visibleCount = 0;

        listItems.forEach(item => {
            const itemLantai = item.getAttribute('data-lantai');
            const itemKamar = item.getAttribute('data-kamar');
            const matchLantai = (lantaiFilter === 'all' || lantaiFilter === itemLantai);
            const matchKamar = (kamarFilter === 'all' || kamarFilter === itemKamar);

            if (matchLantai && matchKamar) {
                item.style.display = ''; // Gunakan string kosong untuk display row
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (filterEmptyMessage) {
            filterEmptyMessage.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }
</script>
</body>
</html>