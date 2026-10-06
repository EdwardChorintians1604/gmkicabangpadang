<div class="container py-8 sm:py-12">
    <!-- Page Header with Animate.css -->
    <div class="animate__animated animate__fadeInDown mb-12 text-center">
        <div class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-2">Kepemimpinan Cabang</div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">Struktur BPC & BPK GMKI Padang</h1>
        <p class="text-slate-600 max-w-2xl mx-auto text-sm sm:text-base font-medium">
            Badan Pengurus Cabang (BPC) dan Badan Pengurus Keuangan (BPK) Gerakan Mahasiswa Kristen Indonesia Cabang Padang. Mengabdi dengan integritas, kerendahan hati, dan keteladanan.
        </p>
    </div>

    <?php if (!empty($structure)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 animate__animated animate__fadeInUp">
            <?php foreach ($structure as $officer): ?>
                <?php 
                    $isBpk = (stripos($officer['bidang'] ?? '', 'BPK') !== false || stripos($officer['bidang'] ?? '', 'Keuangan') !== false);
                ?>
                <div class="card rounded-2xl shadow-sm hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 bg-white text-center border border-slate-200/80 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="w-28 h-28 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary/20 shadow-inner bg-slate-100 ring-2 ring-primary/10">
                            <?php if (!empty($officer['foto'])): ?>
                                <img src="/uploads/organisasi/<?= e($officer['foto']) ?>" alt="<?= e($officer['nama']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <img src="<?= asset('images/default-profile.png') ?>" alt="<?= e($officer['nama']) ?>" class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1 leading-snug">
                            <?= e($officer['nama']) ?>
                        </h3>
                        <div class="text-xs sm:text-sm font-bold text-amber-600 mb-2">
                            <?= e($officer['jabatan']) ?>
                        </div>
                        <div class="inline-block <?= $isBpk ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-blue-100 text-blue-800 border border-blue-200' ?> text-xs font-semibold px-2.5 py-1 rounded-full mb-3">
                            <?= e($officer['bidang']) ?>
                        </div>
                        <div class="text-xs text-slate-400">
                            Masa Bakti: <span class="font-medium text-slate-600"><?= e($officer['periode']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card text-center p-12 rounded-2xl bg-white shadow-sm max-w-lg mx-auto">
            <div class="text-4xl mb-3">👥</div>
            <p class="text-slate-500 font-medium">Data struktur kepengurusan belum dimasukkan ke dalam sistem.</p>
        </div>
    <?php endif; ?>
</div>
