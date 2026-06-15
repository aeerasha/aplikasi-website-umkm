<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width: 500px; border-radius: 15px; border: none;">
        <div class="card-body p-4 text-center">
            
            <h5 class="mb-4">Status Pesanan: <span class="text-primary"><?= htmlspecialchars($pesanan->kode_pesanan ?? 'N/A') ?></span></h5>
            
            <div class="mb-4 p-2 bg-light rounded">
                <small class="text-muted d-block">Status Pembayaran:</small>
                <?php 
                    // Menggunakan 'status' karena di tabel pembayaran Anda hanya ada kolom 'status'
                    $statusBayar = $pesanan->status ?? 'pending'; 
                ?>
                <span class="badge <?= $statusBayar == 'lunas' ? 'bg-success' : 'bg-warning text-dark' ?>">
                    <?= strtoupper($statusBayar) ?>
                </span>
            </div>

            <?php 
            $stages = ['pending', 'diproses', 'siap diambil', 'selesai'];
            $currentStatus = $pesanan->status ?? 'pending';
            $currentIdx = array_search($currentStatus, $stages);
            if ($currentIdx === false) $currentIdx = 0;
            $progressWidth = ($currentIdx / (count($stages) - 1)) * 100;
            ?>

            <div class="d-flex justify-content-between mb-4 position-relative px-2">
                <div class="progress position-absolute" style="height: 4px; top: 15px; left: 10%; right: 10%; z-index: 1; width: 80%;">
                    <div class="progress-bar bg-success" style="width: <?= $progressWidth ?>%; transition: width 0.5s ease-in-out;"></div>
                </div>
                
                <?php foreach ($stages as $index => $stage): ?>
                    <div class="text-center" style="z-index: 2;">
                        <div class="rounded-circle <?= $index <= $currentIdx ? 'bg-success text-white' : 'bg-light border text-muted' ?>" 
                             style="width: 30px; height: 30px; line-height: 30px; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                            <?= $index + 1 ?>
                        </div>
                        <small class="d-block mt-1" style="font-size: 0.7rem; font-weight: <?= $index == $currentIdx ? 'bold' : 'normal' ?>;">
                            <?= ucfirst(str_replace('_', ' ', $stage)) ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="alert mt-4 <?= $currentStatus == 'selesai' ? 'alert-success' : 'alert-info' ?>">
                <strong><?= strtoupper(str_replace('_', ' ', $currentStatus)) ?></strong>
                <p class="mb-0 small mt-2">
                    <?php 
                    $messages = [
                        'pending'      => "Pesanan sedang dalam antrian.",
                        'diproses'     => "Pesanan sedang disiapkan oleh tim kami.",
                        'siap diambil' => "Pesanan sudah siap! Silakan ambil di kasir.",
                        'selesai'      => "Terima kasih telah berbelanja!"
                    ];
                    echo $messages[$currentStatus] ?? "Status sedang diperbarui.";
                    ?>
                </p>
            </div>

            <a href="/customer/katalog" class="btn btn-outline-primary mt-3 w-100">Kembali ke Katalog</a>
        </div>
    </div>
</div>