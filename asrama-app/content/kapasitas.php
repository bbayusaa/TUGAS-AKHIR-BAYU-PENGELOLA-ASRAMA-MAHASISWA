<section class="card">
    <h2>📊 Info Kapasitas per Lantai</h2>
    
    <div class="table-responsive">
        <table class="data-table capacity-table">
            <thead>
                <tr>
                    <th>🏢 Lantai</th>
                    <th>🛏️ Kamar Single (Terpakai / Total)</th>
                    <th>👥 Kamar Double (Terpakai / Total)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kapasitas as $lantai => $kap): ?>
                    <tr>
                        <td>Lantai <?php echo $lantai; ?></td>
                        <td><?php echo $terpakai[$lantai]['Single']; ?> / <?php echo $kap['Single']; ?></td>
                        <td><?php echo $terpakai[$lantai]['Double']; ?> / <?php echo $kap['Double']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form action="proses.php" method="post" style="margin-top: 15px;">
        <input type="hidden" name="aksi" value="tempatkan" />
        <button type="submit" class="btn success">➡️ Tempatkan dari Antrian</button>
    </form>
</section>