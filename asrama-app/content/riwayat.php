<section class="card">
    <h2>📜 Riwayat Keluar</h2>
    <?php if (empty($riwayat)): ?>
        <p class="empty">Belum ada riwayat.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>👤 Nama</th>
                        <th>📞 WA 1 / WA 2</th>
                        <th>🏢 Lt.</th>
                        <th>🛏️ Tipe</th>
                        <th>📅 Mulai</th>
                        <th>⏰ Berakhir</th>
                        <th>⏳ Lama (Bln)</th>
                        <th>💰 Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $riwayat_tampil = array_reverse($riwayat); ?>
                    <?php foreach ($riwayat_tampil as $p): ?>
                        <?php
                        $namaTampil = ($p['kamar'] === 'Double' && !empty($p['nama2']))
                            ? "{$p['nama1']} & {$p['nama2']}"
                            : ($p['nama1'] ?? '');
                        $noWaTampil = ($p['kamar'] === 'Double' && !empty($p['no_wa2']))
                            ? "{$p['no_wa1']} / {$p['no_wa2']}"
                            : ($p['no_wa1'] ?? 'N/A');
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($namaTampil); ?></td>
                            <td><?php echo htmlspecialchars($noWaTampil); ?></td>
                            <td><?php echo (int)$p['lantai']; ?></td>
                            <td><?php echo htmlspecialchars($p['kamar']); ?></td>
                            <td><?php echo format_tanggal_indonesia($p['tanggal_mulai'] ?? 'N/A'); ?></td>
                            <td><?php echo format_tanggal_indonesia($p['tanggal_berakhir'] ?? 'N/A'); ?></td>
                            <td><?php echo (int)$p['lama_bulan']; ?></td>
                            <td>Rp <?php echo number_format((int)$p['biaya_total'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <form action="proses.php" method="post" style="margin-top: 20px;">
        <input type="hidden" name="aksi" value="reset_riwayat" />
        <button type="submit" class="btn danger small">🗑️ Reset Riwayat Saja</button>
    </form>
</section>