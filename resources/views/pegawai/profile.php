<?php $pageTitle = 'Profil Saya'; ?>
<div class="mb-4"><h4 class="fw-800" style="color:#1e293b"><i class="bi bi-person-circle me-2" style="color:#f59e0b"></i>Profil Saya</h4></div>
<div class="row"><div class="col-md-7">
<div class="card"><div class="card-body p-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width:60px;height:60px;font-size:1.4rem;background:#f59e0b">
            <?= strtoupper(substr($user->nama??'?',0,1)) ?>
        </div>
        <div>
            <h5 class="mb-0 fw-bold"><?= e($user->nama??'') ?></h5>
            <span class="badge bg-warning text-dark"><?= e($user->jabatan??'Pegawai') ?></span>
        </div>
    </div>
    <table class="table table-borderless">
        <tr><td class="text-muted" width="140">Email</td><td><strong><?= e($user->email??'') ?></strong></td></tr>
        <tr><td class="text-muted">Telepon</td><td><?= e($user->telepon??'-') ?></td></tr>
        <tr><td class="text-muted">Alamat</td><td><?= e($user->alamat??'-') ?></td></tr>
        <tr><td class="text-muted">Jabatan</td><td><?= e($user->jabatan??'-') ?></td></tr>
        <tr><td class="text-muted">Gaji</td><td><?= formatRupiah($user->gaji??0) ?></td></tr>
        <tr><td class="text-muted">Bergabung</td><td><?= date('d F Y', strtotime($user->created_at??'now')) ?></td></tr>
    </table>
</div></div>
</div></div>
