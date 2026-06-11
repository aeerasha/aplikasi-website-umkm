<div class="page-header d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
    <h4><i class="bi bi-star-fill me-2 text-warning"></i>Daftar Ulasan & Rating Pelanggan</h4>
    <span class="badge bg-purple text-white px-3 py-2 rounded-pill shadow-sm" style="background-color: #6f42c1;">
        <i class="bi bi-shield-lock-fill me-1"></i> Mode Moderator (Read-Only)
    </span>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-3">No</th>
                        <th>Nama Pelanggan</th>
                        <th>Produk</th>
                        <th>Rating</th>
                        <th>Isi Ulasan</th>
                        <th width="15%" class="text-center pe-3">Tanggal Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ulasan)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-chat-square-dots display-6 d-block mb-2 text-secondary"></i>
                                Belum ada ulasan dari pelanggan untuk saat ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($ulasan as $u): ?>
                            <tr>
                                <td class="ps-3"><?= $no++ ?></td>
                                
                                <td>
                                    <?php if ($u->is_anonymous == 1): ?>
                                        <span class="text-muted font-monospace bg-light px-2 py-1 rounded border border-dashed" style="font-size: 0.85rem;">
                                            <i class="bi bi-eye-slash-fill text-secondary me-1"></i> Anonim
                                        </span>
                                    <?php else: ?>
                                        <strong class="text-dark"><?= e($u->nama_user ?? 'Umum / Tamu') ?></strong>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <span class="badge bg-light text-dark border"><?= e($u->nama_produk ?? 'Produk Umum') ?></span>
                                </td>
                                
                                <td>
                                    <div class="text-warning">
                                        <?php 
                                        $stars = (int)$u->rating;
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $stars) {
                                                echo '<i class="bi bi-star-fill me-1"></i>';
                                            } else {
                                                echo '<i class="bi bi-star me-1 text-muted"></i>';
                                            }
                                        }
                                        ?>
                                        <small class="text-muted ms-1">(<?= $stars ?>/5)</small>
                                    </div>
                                </td>
                                
                                <td>
                                    <p class="mb-0 text-secondary" style="max-width: 400px; white-space: normal; font-size: 0.88rem; font-style: italic;">
                                        "<?= e($u->ulasan ?? $u->isi ?? $u->komentar ?? $u->text ?? 'Tidak ada teks ulasan') ?>"
                                    </p>
                                </td>
                                
                                <td class="text-center pe-3 text-muted">
                                    <small><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($u->created_at ?? date('Y-m-d'))) ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>