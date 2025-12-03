<section class="card">
    <h2>➕ Tambah Calon Penghuni</h2>
    <form action="proses.php" method="post" id="formPenghuni" autocomplete="off">
        <input type="hidden" name="aksi" value="tambah_antrian" />

        <label for="kamarSelect">Jenis Kamar
            <select name="kamar" id="kamarSelect" required>
                <option value="Single" selected>Single (1 orang)</option>
                <option value="Double">Double (2 orang)</option>
            </select>
        </label>

        <label for="lantaiSelect">Lantai
            <select name="lantai" id="lantaiSelect" required>
                <option value="1" selected>Lantai 1</option>
                <option value="2">Lantai 2</option>
                <option value="3">Lantai 3</option>
            </select>
        </label>

        <div id="namaFields" style="margin-top:8px;"></div> 

        <label for="tanggal_mulai">📅 Tanggal Mulai Sewa
            <input type="date" id="tanggal_mulai" name="tanggal_mulai" required />
        </label>

        <label for="lama_bulan">⏳ Lama Tinggal (bulan)
            <input type="number" id="lama_bulan" name="lama_bulan" min="1" max="12" value="1" required />
        </label>

        <button type="submit" class="btn primary">🚀 Tambah ke Antrian</button>
    </form>
</section>