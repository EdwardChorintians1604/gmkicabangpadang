<?php
$role = $user['role'] ?? '';
$titles = [
    'inventory' => 'Inventaris GMKI',
    'archive' => 'Arsip Surat Penting',
    'finance' => 'Laporan Keuangan Cabang',
    'strategies' => 'Strategi Organisasi',
    'coordination' => 'Ruang Koordinasi BPC',
];
$backPath = match ($role) {
    'admin' => '/admin/dashboard',
    'ketcab' => '/ketcab/dashboard',
    default => '/ruang-kerja',
};
$backLabel = $role === 'admin' ? 'Dashboard Admin' : 'Ruang kerja';
?>
<header class="flex justify-between items-center" style="margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <p class="text-muted" style="margin:0;"><a href="<?= e($backPath) ?>"><?= e($backLabel) ?></a> / <?= e($titles[$section] ?? '') ?></p>
        <h1 style="font-size:1.75rem;font-weight:800;color:var(--primary);margin:.25rem 0;"><?= e($titles[$section] ?? '') ?></h1>
    </div>
</header>

<?php if ($section === 'inventory'): ?>
    <?php if (can('inventory.manage')): ?>
        <section class="card" style="margin-bottom:1.25rem;"><div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 1rem;">Tambah inventaris</h2>
            <form method="post" action="/sekcab/inventaris" class="grid grid-cols-3 gap-4">
                <?= csrf_field() ?>
                <label>Nama barang<input class="form-control" name="name" maxlength="180" required></label>
                <label>Kategori<input class="form-control" name="category" maxlength="100" required></label>
                <label>Jumlah<input class="form-control" name="quantity" type="number" min="0" step="0.01" value="1" required></label>
                <label>Satuan<input class="form-control" name="unit" maxlength="40" value="unit" required></label>
                <label>Lokasi<input class="form-control" name="location" maxlength="180"></label>
                <label>Kondisi<select class="form-control" name="item_condition"><option>Baik</option><option>Perlu Perbaikan</option><option>Rusak</option></select></label>
                <label style="grid-column:1/-1;">Catatan<textarea class="form-control" name="notes" rows="2"></textarea></label>
                <div><button class="btn btn-primary" type="submit">Simpan inventaris</button></div>
            </form>
        </div></section>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($items as $item): ?>
            <article class="card"><div class="card-body">
                <?php if (can('inventory.manage')): ?>
                    <div class="flex gap-2" style="margin-top:.75rem;">
                    <form method="post" action="/sekcab/inventaris">
                        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                        <div class="grid grid-cols-2 gap-3">
                            <label>Nama barang<input class="form-control" name="name" value="<?= e($item['name']) ?>" required></label>
                            <label>Kategori<input class="form-control" name="category" value="<?= e($item['category']) ?>" required></label>
                            <label>Jumlah<input class="form-control" name="quantity" type="number" min="0" step="0.01" value="<?= e($item['quantity']) ?>" required></label>
                            <label>Satuan<input class="form-control" name="unit" value="<?= e($item['unit']) ?>" required></label>
                            <label>Lokasi<input class="form-control" name="location" value="<?= e($item['location'] ?? '') ?>"></label>
                            <label>Kondisi<select class="form-control" name="item_condition"><?php foreach (['Baik', 'Perlu Perbaikan', 'Rusak'] as $condition): ?><option <?= $item['item_condition'] === $condition ? 'selected' : '' ?>><?= e($condition) ?></option><?php endforeach; ?></select></label>
                            <label style="grid-column:1/-1;">Catatan<textarea class="form-control" name="notes" rows="2"><?= e($item['notes'] ?? '') ?></textarea></label>
                        </div>
                        <button class="btn btn-primary btn-sm" type="submit">Simpan perubahan</button>
                    </form>
                    <form method="post" action="/sekcab/inventaris/<?= (int)$item['id'] ?>/delete" data-confirm="Hapus data inventaris ini?"><?= csrf_field() ?><button class="btn btn-danger btn-sm" type="submit">Hapus</button></form>
                    </div>
                <?php else: ?>
                    <h2><?= e($item['name']) ?></h2><p><?= e($item['category']) ?> · <?= e($item['quantity']) ?> <?= e($item['unit']) ?></p><p><?= e($item['location'] ?? 'Lokasi belum diisi') ?> · <?= e($item['item_condition']) ?></p>
                    <?php if (!empty($item['notes'])): ?><p><?= nl2br(e($item['notes'])) ?></p><?php endif; ?>
                <?php endif; ?>
            </div></article>
        <?php endforeach; ?>
        <?php if (!$items): ?><div class="card"><div class="card-body text-muted">Belum ada inventaris yang dicatat.</div></div><?php endif; ?>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

<?php elseif ($section === 'archive'): ?>
    <?php if (can('archive.manage')): ?>
        <section class="card" style="margin-bottom:1.25rem;"><div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 1rem;">Simpan surat atau dokumen</h2>
            <p class="text-muted">Berkas bersifat privat, maksimal 10 MB. Format: PDF, Word, Excel, ODT, JPG, PNG.</p>
            <form method="post" action="/sekcab/arsip" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
                <?= csrf_field() ?>
                <label>Judul dokumen<input class="form-control" name="title" maxlength="180" required></label>
                <label>Berkas<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png" required></label>
                <label style="grid-column:1/-1;">Keterangan<textarea class="form-control" name="description" rows="2"></textarea></label>
                <div><button class="btn btn-primary" type="submit">Unggah ke arsip</button></div>
            </form>
        </div></section>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($documents as $document): ?><article class="card"><div class="card-body">
            <form method="post" action="/sekcab/arsip" enctype="multipart/form-data">
                <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$document['id'] ?>">
                <label>Judul dokumen<input class="form-control" name="title" maxlength="180" value="<?= e($document['title']) ?>" required></label>
                <label style="display:block;margin-top:.5rem;">Keterangan<textarea class="form-control" name="description" rows="2"><?= e($document['description'] ?? '') ?></textarea></label>
                <label style="display:block;margin-top:.5rem;">Ganti berkas (opsional)<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png"></label>
                <p class="text-muted"><?= e($document['original_name']) ?> · <?= e($document['nama_lengkap']) ?> · <?= e($document['created_at']) ?></p>
                <div class="flex gap-2"><button class="btn btn-primary btn-sm" type="submit">Simpan perubahan</button><a class="btn btn-outline btn-sm" href="/dokumen/<?= (int)$document['id'] ?>/unduh">Unduh</a></div>
            </form>
            <form method="post" action="/sekcab/arsip/<?= (int)$document['id'] ?>/delete" data-confirm="Hapus dokumen ini?" style="margin-top:.5rem;"><?= csrf_field() ?><button class="btn btn-danger btn-sm" type="submit">Hapus arsip</button></form>
        </div></article><?php endforeach; ?>
        <?php if (!$documents): ?><div class="card"><div class="card-body text-center text-muted">Arsip masih kosong.</div></div><?php endif; ?>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

<?php elseif ($section === 'finance'): ?>
    <section class="card" style="margin-bottom:1.25rem;"><div class="card-body flex justify-between items-center" style="gap:1rem;flex-wrap:wrap;">
        <div><strong>Ekspor laporan untuk rekap</strong><p class="text-muted" style="margin:.25rem 0 0;">Pembuatan CSV berjalan di background agar tidak menahan halaman.</p></div>
        <div class="flex items-center gap-2">
            <a class="btn btn-outline" href="/ruang-kerja/antrian">Lihat antrean</a>
            <form method="post" action="/ruang-kerja/antrian/laporan-keuangan" style="margin:0;">
                <?= csrf_field() ?><button class="btn btn-primary" type="submit">Buat ekspor CSV</button>
            </form>
        </div>
    </div></section>
    <?php if ($role === 'bencab'): ?>
        <section class="card" style="margin-bottom:1.25rem;"><div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 1rem;">Tambah laporan keuangan</h2>
            <form method="post" action="/bencab/laporan" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
                <?= csrf_field() ?>
                <label>Judul laporan<input class="form-control" name="title" maxlength="180" required></label>
                <label>Periode<input class="form-control" name="period" placeholder="Contoh: Triwulan I 2026" maxlength="40" required></label>
                <label>Jumlah (Rp)<input class="form-control" name="amount" type="number" min="0" step="0.01" required></label>
                <label>Berkas laporan<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png" required></label>
                <label style="grid-column:1/-1;">Ringkasan/keterangan<textarea class="form-control" name="description" rows="2"></textarea></label>
                <div><button class="btn btn-primary" type="submit">Simpan laporan</button></div>
            </form>
        </div></section>
        <p class="text-muted">Anda hanya dapat mengelola laporan yang diunggah oleh akun Anda.</p>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($documents as $document): ?><article class="card"><div class="card-body">
            <?php if ($role === 'bencab'): ?>
                <div class="flex gap-2">
                <form method="post" action="/bencab/laporan" enctype="multipart/form-data">
                    <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$document['id'] ?>">
                    <label>Judul<input class="form-control" name="title" value="<?= e($document['title']) ?>" required></label>
                    <div class="grid grid-cols-2 gap-3" style="margin-top:.5rem;">
                        <label>Periode<input class="form-control" name="period" value="<?= e($document['period']) ?>" required></label>
                        <label>Jumlah (Rp)<input class="form-control" name="amount" type="number" min="0" step="0.01" value="<?= e($document['amount']) ?>" required></label>
                    </div>
                    <label style="display:block;margin-top:.5rem;">Ganti berkas (opsional)<input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.jpg,.jpeg,.png"></label>
                    <label style="display:block;margin-top:.5rem;">Ringkasan<textarea class="form-control" name="description" rows="2"><?= e($document['description'] ?? '') ?></textarea></label>
                    <p class="text-muted"><a href="/dokumen/<?= (int)$document['id'] ?>/unduh">Unduh berkas saat ini: <?= e($document['original_name']) ?></a></p>
                    <button class="btn btn-primary btn-sm" type="submit">Perbarui</button>
                </form>
                <form method="post" action="/bencab/laporan/<?= (int)$document['id'] ?>/delete" data-confirm="Hapus laporan ini?"><?= csrf_field() ?><button class="btn btn-danger btn-sm" type="submit">Hapus</button></form>
                </div>
            <?php else: ?>
                <h2><?= e($document['title']) ?></h2><p class="text-muted"><?= e($document['period']) ?> · diunggah oleh <?= e($document['nama_lengkap']) ?></p>
                <strong>Rp <?= number_format((float)$document['amount'], 2, ',', '.') ?></strong>
                <?php if (!empty($document['description'])): ?><p><?= nl2br(e($document['description'])) ?></p><?php endif; ?>
                <a class="btn btn-outline btn-sm" href="/dokumen/<?= (int)$document['id'] ?>/unduh">Unduh laporan</a>
            <?php endif; ?>
        </div></article><?php endforeach; ?>
        <?php if (!$documents): ?><div class="card"><div class="card-body text-muted">Belum ada laporan keuangan yang diterbitkan.</div></div><?php endif; ?>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

<?php elseif ($section === 'strategies'): ?>
    <?php if (can('strategy.manage')): ?>
        <section class="card" style="margin-bottom:1.25rem;"><div class="card-body">
            <h2 style="font-size:1.1rem;font-weight:700;margin:0 0 1rem;">Catat arah strategis</h2>
            <form method="post" action="/ketcab/strategi" class="grid grid-cols-2 gap-4">
                <?= csrf_field() ?><label>Judul strategi<input class="form-control" name="title" maxlength="180" required></label>
                <label>Status<select class="form-control" name="status"><option>Rencana</option><option>Berjalan</option><option>Selesai</option></select></label>
                <label>Tanggal target<input class="form-control" name="target_date" type="date"></label>
                <label style="grid-column:1/-1;">Tujuan dan langkah<textarea class="form-control" name="objective" rows="3" required></textarea></label>
                <div><button class="btn btn-primary" type="submit">Simpan strategi</button></div>
            </form>
        </div></section>
    <?php endif; ?>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($strategies as $strategy): ?><article class="card"><div class="card-body">
            <?php if (can('strategy.manage')): ?>
                <div class="flex gap-2">
                <form method="post" action="/ketcab/strategi">
                    <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$strategy['id'] ?>">
                    <label>Judul<input class="form-control" name="title" value="<?= e($strategy['title']) ?>" required></label>
                    <div class="grid grid-cols-2 gap-3" style="margin-top:.5rem;"><label>Status<select class="form-control" name="status"><?php foreach (['Rencana', 'Berjalan', 'Selesai'] as $status): ?><option <?= $strategy['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select></label>
                    <label>Target<input class="form-control" type="date" name="target_date" value="<?= e($strategy['target_date'] ?? '') ?>"></label></div>
                    <label style="display:block;margin-top:.5rem;">Tujuan dan langkah<textarea class="form-control" name="objective" rows="3" required><?= e($strategy['objective']) ?></textarea></label>
                    <button class="btn btn-primary btn-sm" style="margin-top:.5rem;">Simpan</button>
                </form>
                <form method="post" action="/ketcab/strategi/<?= (int)$strategy['id'] ?>/delete" data-confirm="Hapus catatan strategi ini?"><?= csrf_field() ?><button class="btn btn-danger btn-sm">Hapus</button></form>
                </div>
            <?php else: ?>
                <h2><?= e($strategy['title']) ?></h2><p><?= nl2br(e($strategy['objective'])) ?></p><span class="badge badge-primary"><?= e($strategy['status']) ?></span>
                <?php if ($strategy['target_date']): ?><span class="text-muted">Target <?= e($strategy['target_date']) ?></span><?php endif; ?>
            <?php endif; ?>
        </div></article><?php endforeach; ?>
        <?php if (!$strategies): ?><div class="card"><div class="card-body text-muted">Belum ada strategi yang dicatat.</div></div><?php endif; ?>
    </div>
    <?= partial('partials.pagination', $pagination) ?>

<?php elseif ($section === 'coordination'): ?>
    <?php
    $senderRoleNames = $participants;
    $senderRoleNames['admin'] = 'Sekfung Medko';
    $currentParticipantRole = $role === 'admin' ? 'sekfung_medko' : $role;
    $lastMessage = $messages ? end($messages) : null;
    ?>
    <section class="coordination-chat"
        data-messages-url="<?= e($messagesUrl) ?>"
        data-latest-id="<?= $messages ? (int)end($messages)['id'] : 0 ?>"
        data-oldest-id="<?= $messages ? (int)$messages[0]['id'] : 0 ?>"
        data-has-older="<?= $hasOlderMessages ? 'true' : 'false' ?>">
        <aside class="coordination-chat-list" aria-label="Daftar chat">
            <div class="coordination-list-header">
                <div><strong>Chat</strong><span>GMKI Cabang Padang</span></div>
                <span class="coordination-compose-icon" aria-hidden="true">✎</span>
            </div>
            <div class="coordination-search">
                <span aria-hidden="true">⌕</span>
                <span>Cari percakapan</span>
            </div>
            <div class="coordination-contacts-heading">Percakapan</div>
            <div class="coordination-contact active" aria-current="page">
                <span class="coordination-group-avatar" aria-hidden="true"><span>G</span><i>●</i></span>
                <span class="coordination-contact-copy">
                    <strong>Koordinasi Pengurus BPC</strong>
                    <small><?= $lastMessage ? e($lastMessage['sender_name'] . ': ' . mb_strimwidth((string)$lastMessage['body'], 0, 52, '…')) : 'Ketcab, Sekcab, Bencab, dan Medko' ?></small>
                </span>
                <time><?= $lastMessage ? e(date('H:i', strtotime($lastMessage['created_at']))) : '' ?></time>
            </div>
            <div class="coordination-list-note">
                <strong>Grup bersama</strong>
                <p>Semua pesan koordinasi tampil di percakapan yang sama untuk seluruh pengurus.</p>
            </div>
            <div class="coordination-member-list" aria-label="Anggota grup">
                <span>ANGGOTA GRUP</span>
            <?php foreach ($participants as $participantRole => $participantName): ?>
                <?php $isCurrentUser = $participantRole === $currentParticipantRole; ?>
                <?php $initial = strtoupper(mb_substr($participantName, 0, 1)); ?>
                <div class="coordination-member">
                    <span class="coordination-avatar"><?= e($initial) ?></span>
                    <span><strong><?= e($participantName) ?></strong><small><?= $isCurrentUser ? 'Anda' : 'Anggota' ?></small></span>
                </div>
            <?php endforeach; ?>
            </div>
        </aside>
        <div class="coordination-conversation">
            <header class="coordination-conversation-header">
                <span class="coordination-group-avatar coordination-header-avatar" aria-hidden="true"><span>G</span><i>●</i></span>
                <div><strong>Koordinasi Pengurus BPC</strong><small><?= count($participants) ?> pengurus · Ketcab, Sekcab, Bencab, Medko</small></div>
                <span class="coordination-live" aria-live="polite">Terhubung</span>
            </header>
            <div class="coordination-messages" id="coordinationMessages" aria-live="polite" aria-label="Pesan percakapan">
                <button type="button" class="coordination-load-older" id="coordinationLoadOlder" <?= $hasOlderMessages ? '' : 'hidden' ?>>Muat pesan sebelumnya</button>
                <?php foreach ($messages as $message): ?>
                    <?php $ownMessage = (int)$message['sender_id'] === (int)$currentUserId; ?>
                    <article class="coordination-message <?= $ownMessage ? 'outgoing' : 'incoming' ?>" data-message-id="<?= (int)$message['id'] ?>">
                        <?php if (!$ownMessage): ?><strong class="coordination-sender"><?= e($message['sender_name']) ?> <span>· <?= e($senderRoleNames[$message['sender_role']] ?? $message['sender_role']) ?></span></strong><?php endif; ?>
                        <p><?= nl2br(e($message['body'])) ?></p>
                        <time datetime="<?= e($message['created_at']) ?>"><?= e(date('H:i', strtotime($message['created_at']))) ?></time>
                    </article>
                <?php endforeach; ?>
                <?php if (!$messages): ?>
                    <div class="coordination-welcome" id="coordinationEmpty">
                        <span class="coordination-welcome-icon" aria-hidden="true">G</span>
                        <strong>Koordinasi Pengurus BPC</strong>
                        <p>Ruang bersama untuk Ketcab, Sekcab, Bencab, dan Sekfung Medko.</p>
                        <small>Mulai percakapan dengan mengirim pesan di bawah.</small>
                    </div>
                <?php endif; ?>
            </div>
            <form class="coordination-composer" id="coordinationForm" method="post" data-user-id="<?= (int)$currentUserId ?>" action="<?= e($role === 'admin' ? '/admin/koordinasi' : ($role === 'ketcab' ? '/ketcab/koordinasi' : '/ruang-kerja/koordinasi')) ?>">
                <?= csrf_field() ?>
                <label class="sr-only" for="coordinationBody">Tulis pesan</label>
                <textarea id="coordinationBody" name="body" rows="1" maxlength="5000" placeholder="Ketik pesan" required></textarea>
                <button type="submit" aria-label="Kirim pesan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m22 2-7 20-4-9-9-4 20-7Z"/><path d="M22 2 11 13"/></svg></button>
                <p class="coordination-error" id="coordinationError" role="alert" hidden></p>
            </form>
        </div>
    </section>
<?php endif; ?>
