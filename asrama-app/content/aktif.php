<section class="card">
    <h2>👥 Penghuni Aktif</h2>

    <div class="filter-controls">
        <label>Filter Lantai:
            <select id="filterLantai" onchange="filterAktif()">
                <option value="all">Semua</option>
                <option value="1">Lantai 1</option>
                <option value="2">Lantai 2</option>
                <option value="3">Lantai 3</option>
            </select>
        </label>
        <label>Filter Kamar:
            <select id="filterKamar" onchange="filterAktif()">
                <option value="all">Semua</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
            </select>
        </label>
    </div>

    <?php if (empty($aktif)): ?>
        <p class="empty" id="noActiveResidents">Belum ada penghuni aktif.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table" id="listAktifTable">
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="listAktif">
                    <?php foreach ($aktif as $i => $p): ?>
                        <?php
                        $namaTampil = ($p['kamar'] === 'Double' && !empty($p['nama2']))
                            ? "{$p['nama1']} & {$p['nama2']}"
                            : ($p['nama1'] ?? '');
                        $statusClass = (isset($p['tanggal_berakhir']) && $p['tanggal_berakhir'] < date('Y-m-d')) ? 'status-expired' : '';
                        $noWaTampil = ($p['kamar'] === 'Double' && !empty($p['no_wa2']))
                            ? "{$p['no_wa1']} / {$p['no_wa2']}"
                            : ($p['no_wa1'] ?? 'N/A');
                        ?>
                        <tr class="<?php echo $statusClass; ?>" data-lantai="<?php echo (int)$p['lantai']; ?>" data-kamar="<?php echo htmlspecialchars($p['kamar']); ?>">
                            <td><?php echo htmlspecialchars($namaTampil); ?></td>
                            <td><?php echo htmlspecialchars($noWaTampil); ?></td>
                            <td><?php echo (int)$p['lantai']; ?></td>
                            <td><?php echo htmlspecialchars($p['kamar']); ?></td>
                            <td><?php echo format_tanggal_indonesia($p['tanggal_mulai'] ?? 'N/A'); ?></td>
                            <td><?php echo format_tanggal_indonesia($p['tanggal_berakhir'] ?? 'N/A'); ?></td>
                            <td><?php echo (int)$p['lama_bulan']; ?></td>
                            <td>Rp <?php echo number_format((int)$p['biaya_total'], 0, ',', '.'); ?></td>
                            <td class="action-cell">
                                <form action="proses.php" method="post" class="inline">
                                    <input type="hidden" name="aksi" value="keluarkan" />
                                    <input type="hidden" name="index" value="<?php echo (int)$i; ?>" />
                                    <button type="submit" class="btn danger small" title="Keluarkan">🚪</button>
                                </form>
                                <form action="proses.php" method="post" class="inline">
                                    <input type="hidden" name="aksi" value="perpanjang" />
                                    <input type="hidden" name="index" value="<?php echo (int)$i; ?>" />
                                    <input type="number" name="lama_bulan_baru" min="1" max="12" value="1" style="width:50px; padding:3px;" required />
                                    <button type="submit" class="btn primary small" title="Perpanjang">🔄</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="empty" id="filterEmptyMessage" style="display:none;">Tidak ada yang cocok dengan filter.</p>
    <?php endif; ?>
</section>