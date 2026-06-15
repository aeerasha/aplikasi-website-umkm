<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width: 500px; border-radius: 15px; border: none;">
        <div class="card-body p-4 text-center">
            
            <h5 class="mb-4">Status Pesanan: <span class="text-primary"><?= htmlspecialchars($pesanan->kode_pesanan ?? 'N/A') ?></span></h5>
            
            <div class="mb-4 p-2 bg-light rounded">
                <small class="text-muted d-block">Status Pembayaran:</small>
                <?php 
                    // Mengambil status dari tabel pesanan (fallback ke 'pending' jika null)
                    $statusBayar = $pesanan->status ?? 'pending'; 
                ?>
                <span class="badge <?= $statusBayar == 'lunas' ? 'bg-success' : 'bg-warning text-dark' ?>">
                    <?= strtoupper(str_replace('_', ' ', $statusBayar)) ?>
                </span>
            </div>

            <?php 
            // Logika Progress Bar
            $stages = ['pending', 'diproses', 'siap diambil', 'selesai'];
            $currentStatus = $pesanan->status ?? 'pending';
            $currentIdx = array_search($currentStatus, $stages);
            
            // Jika status tidak ditemukan, set ke 0
            if ($currentIdx === false) $currentIdx = 0;
            
            // Hitung persentase lebar bar (min 10% agar tetap terlihat)
            $progressWidth = ($currentIdx / (count($stages) - 1)) * 100;
            ?>

            <div class="d-flex justify-content-between mb-4 position-relative px-2">
                <div class="progress position-absolute" style="height: 4px; top: 15px; left: 10%; right: 10%; z-index: 1; width: 80%; background-color: #e9ecef;">
                    <div class="progress-bar bg-success" style="width: <?= $progressWidth ?>%; min-width: 10%; transition: width 0.5s ease-in-out;"></div>
                </div>
                
                <?php foreach ($stages as $index => $stage): ?>
                    <div class="text-center" style="z-index: 2;">
                        <div class="rounded-circle <?= $index <= $currentIdx ? 'bg-success text-white' : 'bg-light border text-muted' ?>" 
                             style="width: 30px; height: 30px; line-height: 30px; margin: 0 auto; font-size: 0.8rem; font-weight: bold; border: 2px solid <?= $index <= $currentIdx ? '#198754' : '#dee2e6' ?>;">
                            <?= $index + 1 ?>
                        </div>
                        <small class="d-block mt-1" style="font-size: 0.7rem; font-weight: <?= $index == $currentIdx ? 'bold' : 'normal' ?>;">
                            <?= ucfirst(str_replace('_', ' ', $stage)) ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="alert mt-4 <?= $currentStatus == 'selesai' ? 'alert-success' : 'alert-info' ?>">
                <p class="mb-0 small">
                    <?php 
                    $messages = [
                        'pending'      => "Pesanan sedang dalam antrian.",
                        'diproses'     => "Pesanan sedang disiapkan oleh tim kami.",
                        'siap diambil' => "Pesanan sudah siap! Silakan ambil di kasir.",
                        'selesai'      => "Terima kasih telah berbelanja!"
                    ];
                    ?>
                </p>
            </div>

        </div>
    </div>
</div>