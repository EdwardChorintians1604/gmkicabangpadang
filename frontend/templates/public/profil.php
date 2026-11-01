<div class="container py-8 sm:py-12">
    <!-- Page Header with Animate.css -->
    <div class="animate__animated animate__fadeInDown mb-12 text-center">
        <div class="section-tag uppercase text-amber-600 font-bold tracking-wider text-xs mb-2">Mengenal Lebih Dekat
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">Profil &amp; Sejarah Cabang</h1>
        <p class="text-slate-600 max-w-2xl mx-auto text-sm sm:text-base font-medium">
            <?= e($profile['slogan'] ?? 'Ut Omnes Unum Sint — Supaya mereka semua menjadi satu') ?>
        </p>
    </div>

    <!-- ===================================================================== -->
    <!-- SEJARAH PEMBENTUKAN GMKI CABANG PADANG (PERMANENT FRONT-END ARCHIVE)  -->
    <!-- ===================================================================== -->
    <div
        class="card rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mb-10 overflow-hidden bg-white animate__animated animate__fadeInUp border border-slate-200/80">
        <!-- Header Banner Sejarah -->
        <div
            class="px-6 py-5 sm:px-10 sm:py-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 via-white to-amber-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div
                    class="w-12 h-12 rounded-2xl bg-[#0f3d64] text-amber-300 flex items-center justify-center text-2xl shadow-sm flex-shrink-0">
                    📜
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight"
                        style="color: #0f3d64;">
                        Sejarah GMKI Cabang Padang
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Penulis: <strong>GMKI Cabang Padang (Bung Toni Indra Siahaan)</strong> &bull; Rabu, 16 September
                        2015
                    </p>
                </div>
            </div>
            <a href="https://gmki-padang.blogspot.com/2013/02/sejarah-gmki-cabang-padang.html" target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 text-xs font-bold px-3.5 py-2 rounded-xl border border-amber-300/80 bg-amber-50 text-amber-900 hover:bg-amber-100 transition-colors self-start sm:self-center">
                <span>🔗 Sumber Arsip Blog Sejarah</span>
                <span>&nearr;</span>
            </a>
        </div>

        <div class="card-body p-6 sm:p-10 text-slate-700 text-sm sm:text-base leading-relaxed space-y-8">

            <!-- 1. Inisiasi Awal (1984) & Dokumentasi Foto HUT I -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Foto Historis -->
                <div class="lg:col-span-5">
                    <figure class="rounded-2xl overflow-hidden border-2 border-slate-200 bg-slate-50 shadow-sm">
                        <img src="<?= asset('images/sejarah-gmki-padang-1987.jpg') ?>"
                            alt="Dokumentasi HUT I GMKI Cabang Padang (15 Maret 1986 - 15 Maret 1987)"
                            class="w-full h-auto object-cover block" loading="lazy">
                        <figcaption
                            class="p-3.5 text-xs text-slate-600 bg-slate-100/80 border-t border-slate-200 leading-snug text-center">
                            <strong class="text-slate-800 block mb-0.5">Dokumentasi HUT I GMKI Cabang Padang (15 Maret
                                1987)</strong>
                            Spanduk: <em>&ldquo;Selamat Hari Ulang Tahun I Gerakan Mahasiswa Kristen Indonesia (GMKI)
                                Cabang Padang (15-3-1986 &ndash; 15-3-1987) &mdash; Kamu Adalah Saksi-Ku&rdquo;</em>
                        </figcaption>
                    </figure>
                </div>

                <!-- Narasi Pembentukan 1984 -->
                <div class="lg:col-span-7 space-y-4 text-justify">
                    <p>
                        Pembentukan <strong>GMKI Cabang Padang</strong> awalnya telah diinisiasi sejak tahun
                        <strong>1984</strong>, hal tersebut ditandai dengan surat <strong>Permohonan Pembentukan Cabang
                            GMKI</strong> oleh ketujuh anak muda pada masanya kepada Ketua Umum PP GMKI pada tanggal
                        <strong>11 Januari 1984</strong>.
                    </p>
                    <p>
                        Kemudian PP GMKI menanggapi hal ini dengan mengangkat ketujuh orang ini sebagai <strong>Tim
                            Pembentukan Calon Cabang GMKI Padang</strong> melalui Surat Keputusan Nomor: <code
                            class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 font-semibold text-xs sm:text-sm">180348/SU/Int/IV/1984</code>
                        tertanggal <strong>16 April 1984</strong> yang ditandatangani oleh <strong>Alex Litaay</strong>
                        sebagai Ketua Bidang Aksi dan Pelayanan dan <strong>Sunggul Siahaan</strong> sebagai Sekretaris
                        Umum PP GMKI pada masa itu.
                    </p>
                    <p>
                        Sejak itu, dilakukan terus proses-proses komunikasi dan konsolidasi pembentukan cabang kepada
                        seluruh pihak terkait. Pada bulan <strong>Oktober &ndash; Nopember 1984</strong> dilaksanakan
                        pengkaderan calon pengurus GMKI di Padang.
                    </p>
                </div>
            </div>

            <!-- 2. Tabel Ketujuh Inisiator Pembentukan Cabang GMKI di Padang -->
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-3 flex items-center gap-2"
                    style="color: #0f3d64;">
                    <span class="w-2 h-6 rounded-full bg-amber-500 inline-block"></span>
                    <span>Adapun Ketujuh Inisiator Pembentukan Cabang GMKI di Padang:</span>
                </h3>
                <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-2xs">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-[#0f3d64] text-white">
                                <th rowspan="2"
                                    class="py-3 px-4 border-r border-slate-600/40 w-14 text-center font-bold">No.</th>
                                <th rowspan="2" class="py-3 px-4 border-r border-slate-600/40 font-bold">Nama</th>
                                <th colspan="2"
                                    class="py-2 px-4 text-center font-bold border-b border-slate-600/40 bg-[#0b2f4f]">
                                    Anggota GMKI</th>
                            </tr>
                            <tr class="bg-[#154b78] text-amber-200 text-xs uppercase tracking-wider">
                                <th class="py-2 px-4 border-r border-slate-600/40 text-center font-bold w-28">Tahun</th>
                                <th class="py-2 px-4 font-bold">Di</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-700">1</td>
                                <td class="py-2.5 px-4 font-semibold text-slate-900">Pdt. Junus Beeh, S.Th</td>
                                <td class="py-2.5 px-4 text-center text-slate-600">1974</td>
                                <td class="py-2.5 px-4 text-slate-700">Jakarta</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-700">2</td>
                                <td class="py-2.5 px-4 font-semibold text-slate-900">Drs. S.M. Sinambela</td>
                                <td class="py-2.5 px-4 text-center text-slate-600">1965</td>
                                <td class="py-2.5 px-4 text-slate-700">Jakarta</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-700">3</td>
                                <td class="py-2.5 px-4 font-semibold text-slate-900">Drs. Albert T. Panggeso</td>
                                <td class="py-2.5 px-4 text-center text-slate-600">1979</td>
                                <td class="py-2.5 px-4 text-slate-700">Ujung Pandang</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-700">4</td>
                                <td class="py-2.5 px-4 font-semibold text-slate-900">Paulus Sarungu, BA</td>
                                <td class="py-2.5 px-4 text-center text-slate-600">1979</td>
                                <td class="py-2.5 px-4 text-slate-700">Jakarta</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-700">5</td>
                                <td class="py-2.5 px-4 font-semibold text-slate-900">Drs. S.K. Sinulingga</td>
                                <td class="py-2.5 px-4 text-center text-slate-600">1962</td>
                                <td class="py-2.5 px-4 text-slate-700">Medan</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-700">6</td>
                                <td class="py-2.5 px-4 font-semibold text-slate-900">Sere D. Silitonga</td>
                                <td class="py-2.5 px-4 text-center text-slate-600">1965</td>
                                <td class="py-2.5 px-4 text-slate-700">Medan</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td class="py-2.5 px-4 text-center font-bold text-slate-700">7</td>
                                <td class="py-2.5 px-4 font-semibold text-slate-900">Boby R. Manusama P</td>
                                <td class="py-2.5 px-4 text-center text-slate-600">1968</td>
                                <td class="py-2.5 px-4 text-slate-700">Ambon</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Susunan Kepanitiaan Pengkaderan Calon Pengurus GMKI Cabang Padang Tahun 1984 -->
            <div>
                <div class="text-center mb-4">
                    <h3 class="text-base sm:text-lg font-extrabold uppercase tracking-wide text-slate-900"
                        style="color: #0f3d64;">
                        Susunan Kepanitiaan<br>
                        Pengkaderan Calon Pengurus GMKI Cabang Padang<br>
                        Tahun 1984
                    </h3>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-2xs max-w-3xl mx-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-800 w-1/2 align-top border-r border-slate-200 bg-slate-50/60">
                                    <div>I. Ketua I</div>
                                    <div class="pl-4 mt-1">Ketua II</div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900 align-top">
                                    <div>Drs. Albert T. Panggeso</div>
                                    <div class="mt-1">Yos Mandala</div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-800 align-top border-r border-slate-200 bg-slate-50/60">
                                    <div>II. Sekretaris I</div>
                                    <div class="pl-5 mt-1">Sekretaris II</div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900 align-top">
                                    <div>Tadius</div>
                                    <div class="mt-1">E t t y</div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-800 align-top border-r border-slate-200 bg-slate-50/60">
                                    III. Bendahara
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900 align-top">
                                    Robinhot Sinaga
                                </td>
                            </tr>
                            <tr class="bg-slate-100/90">
                                <td colspan="2"
                                    class="py-2.5 px-4 font-extrabold text-slate-800 uppercase tracking-wider text-xs">
                                    IV. Seksi-seksi
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-700 align-middle border-r border-slate-200 pl-6">
                                    1. Dana Koordinator
                                </td>
                                <td class="py-3 px-4 text-slate-800 font-medium">
                                    <div>- Yunizman Zebua</div>
                                    <div>- Alfred Sitoru</div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-700 align-middle border-r border-slate-200 pl-6">
                                    2. Acara Koordinator
                                </td>
                                <td class="py-3 px-4 text-slate-800 font-medium">
                                    <div>- Ganefo Ginting</div>
                                    <div>- Kusmidawaty Marbun</div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-700 align-middle border-r border-slate-200 pl-6">
                                    3. Humas Koordinator
                                </td>
                                <td class="py-3 px-4 text-slate-800 font-medium">
                                    <div>- Bonar Sihombing</div>
                                    <div>- Charles Aritonang</div>
                                    <div>- Tumpak Debataraj</div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-700 align-middle border-r border-slate-200 pl-6">
                                    4. Perlengkapan Koordinator
                                </td>
                                <td class="py-3 px-4 text-slate-800 font-medium">
                                    <div>- Edward Sijabat</div>
                                    <div>- Mei Hasanul</div>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80">
                                <td
                                    class="py-3 px-4 font-bold text-slate-700 align-middle border-r border-slate-200 pl-6">
                                    5. Konsumsi Koordinator
                                </td>
                                <td class="py-3 px-4 text-slate-800 font-medium">
                                    <div>- Kristian Manurung</div>
                                    <div>- Rut Najo</div>
                                    <div>- Rosmauli Girsang</div>
                                    <div>- Ritawaty Butarbutar</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. Peresmian Cabang (15 Maret 1986) & Konperensi Cabang I (1988) -->
            <div class="p-5 sm:p-7 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-4 text-justify">
                <p>
                    Akhirnya, melalui pergumulan yang panjang <em>(lebih dari setahun setelah pengkaderan)</em> GMKI
                    Cabang Padang terbentuk secara resmi melalui Surat Keputusan PP GMKI Nomor: <code
                        class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-slate-800 font-semibold text-xs sm:text-sm">190244/SU/Int/III/1986</code>
                    yang ditandatangani oleh <strong>Ir. F. Robert O. Sitorus</strong> sebagai Ketua dan <strong>Pdt.
                        Dicky M. Mailoa</strong> sebagai Sekretaris Umum. Pengesahan Cabang Padang dan Badan Pengurus
                    Cabang dilaksanakan pada tanggal <strong>15 Maret 1986</strong> di <strong>Gedung Sasana Karya (Jl.
                        Sudirman &ndash; Padang)</strong>. <strong>Drs. Med. Saut Pakpahan</strong> dilantik menjadi
                    Ketua Cabang I GMKI Padang dan <strong>Ganefo Ginting</strong> sebagai Sekretaris Cabang I GMKI
                    Padang serta <strong>Faosiaro Bawamenewi</strong> sebagai Bendahara Cabang I GMKI Padang.
                </p>
                <div>
                    <p class="mb-2">
                        Pada saat peresmian GMKI Cabang Padang anggota GMKI Padang terdaftar sebanyak <strong>106
                            orang</strong>. <strong>48 orang</strong> diantaranya adalah kelompok pelaksana tugas BPC
                        GMKI Padang dengan tugas-tugas:
                    </p>
                    <ol class="list-decimal list-inside space-y-1.5 pl-2 font-medium text-slate-800">
                        <li>Melaksanakan tugas-tugas organisasi sesuai fungsinya (AD/ART GMKI).</li>
                        <li>Tetap menguatkan konsolidasi baik terhadap sesama pelaksana tugas maupun terhadap anggota.
                        </li>
                        <li>Mempersiapkan dan menyelenggarakan Konperensi Cabang I GMKI Padang selambat-lambatnya selama
                            setahun.</li>
                    </ol>
                </div>
                <p>
                    Kemudian pada tanggal <strong>15 &ndash; 18 September 1988</strong> barulah dapat diselenggarakan
                    <strong>Konperensi Cabang I GMKI Padang</strong> dan terpilihlah <strong>Poltak Simanjuntak</strong>
                    dan <strong>Perry Barus</strong> selaku Ketua Cabang dan Sekretaris Cabang.
                </p>
            </div>

            <!-- 5. Urutan Penanggungjawab GMKI Cabang Padang dari Waktu ke Waktu -->
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-2"
                    style="color: #0f3d64;">
                    <span class="w-2 h-6 rounded-full bg-amber-500 inline-block"></span>
                    <span>Berikut adalah urutan penanggungjawab GMKI Cabang Padang dari waktu ke waktu:</span>
                </h3>

                <?php
                $periodeAwal = [
                    [
                        'no' => '1',
                        'periode' => 'Periode 1986 – 1988',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Dr. Saut Pakpahan'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Drs. Ganefo Ginting', 'Ir. Onnessimus Sipahutar (Resuffle)'],
                    ],
                    [
                        'no' => '2',
                        'periode' => 'Periode 1988 – 1990',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Ir. Poltak Simanjuntak'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Perry Barus, SE. Akt'],
                    ],
                    [
                        'no' => '3',
                        'periode' => 'Periode 1990 – 1992',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Yan Winnen Sipayung, SP'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Nesar Situmeang, SE'],
                    ],
                    [
                        'no' => '4',
                        'periode' => 'Periode 1992 – 1994',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Rita Artanti, SP'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Lasmaria Simanjuntak, A.'],
                    ],
                    [
                        'no' => '5',
                        'periode' => 'Periode 1994 – 1996',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Gongo Rikafor Marpaung, SP'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Firdaus H.L. Girsang, S.Pt'],
                    ],
                    [
                        'no' => '6',
                        'periode' => 'Periode 1996 – 1998',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Mangara Tua Tampubolon, SH'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Tiurma Ida Siahaan, SE'],
                    ],
                    [
                        'no' => '7',
                        'periode' => 'Periode 1998 – 2000',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Devi Fatriana Situmorang, SH'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Adriani E.J. Simatupang, A.Md'],
                    ],
                    [
                        'no' => '8',
                        'periode' => 'Periode 2000 – 2002',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Tigor Dolly Simarmata'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Hotmaria Manurung'],
                    ],
                    [
                        'no' => '9',
                        'periode' => 'Periode 2002 – 2004',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Tomson Purba', 'Rudy Hendra Pakpahan (Pj. Ketua)'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Hefwi Haomoan Sinaga'],
                    ],
                    [
                        'no' => '10',
                        'periode' => 'Periode 2004 – 2006 (PAW menjadi 2005 – 2007)',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Agam Junaidi Sinaga', 'Bernando Sinaga (Pj. Ketua)'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Berman Tua Sibuea'],
                    ],
                    [
                        'no' => '11',
                        'periode' => 'Periode 2007 – 2009',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Berman Tua Sibuea', 'Marlina Novita Hutauruk (Pjs)'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Bernando Sinaga'],
                    ],
                    [
                        'no' => '12',
                        'periode' => 'Periode 2010 – 2012',
                        'label_ketua' => 'Ketua Cabang',
                        'ketua' => ['Glocesdo Togar H. Tamba', 'Michael Jeffri Sinabutar (Pj. Ketua)'],
                        'label_sekretaris' => 'Sekretaris Cabang',
                        'sekretaris' => ['Lenny Hot Rometa Aritonang'],
                    ],
                ];

                $periodeLanjutan = [
                    [
                        'no' => '14',
                        'periode' => 'Periode 2012 – 2013',
                        'label_ketua' => 'Ketua',
                        'ketua' => ['Toni Indra Siahaan'],
                        'label_sekretaris' => 'Sekretaris',
                        'sekretaris' => ['Aristanto Tumanggor'],
                    ],
                    [
                        'no' => '15',
                        'periode' => 'Periode 2013 – 2014',
                        'label_ketua' => 'Ketua',
                        'ketua' => ['Oliver Silitonga'],
                        'label_sekretaris' => 'Sekretaris',
                        'sekretaris' => ['Rikki Fernando Sinaga'],
                    ],
                    [
                        'no' => '16',
                        'periode' => 'Periode 2015 – 2017',
                        'label_ketua' => 'Ketua',
                        'ketua' => ['Rikki Fernando Sinaga'],
                        'label_sekretaris' => 'Sekretaris',
                        'sekretaris' => ['Susi Santri Sianturi'],
                    ],
                ];
                ?>

                <!-- Daftar Periode 1 s.d. 12 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <?php foreach ($periodeAwal as $item): ?>
                        <div
                            class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs hover:border-sky-300 transition-colors">
                            <div class="flex items-center gap-2.5 mb-3 pb-2 border-b border-slate-100">
                                <span
                                    class="w-7 h-7 rounded-lg bg-[#0f3d64] text-amber-300 text-xs font-extrabold flex items-center justify-center flex-shrink-0">
                                    <?= e($item['no']) ?>
                                </span>
                                <h4 class="font-extrabold text-sm sm:text-base text-slate-900 m-0" style="color: #0f3d64;">
                                    <?= e($item['periode']) ?>
                                </h4>
                            </div>
                            <div class="space-y-2 text-xs sm:text-sm">
                                <div class="grid grid-cols-12 gap-2">
                                    <div class="col-span-5 text-slate-500 font-semibold"><?= e($item['label_ketua']) ?>
                                    </div>
                                    <div class="col-span-7 font-bold text-slate-800">
                                        <?php foreach ($item['ketua'] as $k): ?>
                                            <div><?= e($k) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-2">
                                    <div class="col-span-5 text-slate-500 font-semibold"><?= e($item['label_sekretaris']) ?>
                                    </div>
                                    <div class="col-span-7 font-semibold text-slate-700">
                                        <?php foreach ($item['sekretaris'] as $s): ?>
                                            <div><?= e($s) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Narasi Transisi Caretaker 2012 & Periode 13 -->
                <div class="rounded-2xl border border-amber-300/80 bg-amber-50/60 p-5 sm:p-6 mb-6 space-y-4">
                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed text-justify m-0">
                        Pada masa bakti <strong>2010 &ndash; 2012</strong>, BPC GMKI Padang diambilalih oleh Pengurus
                        Pusat GMKI (<strong>Caretaker BPC</strong>) dengan alasan lambannya aktivitas organisasi.
                        Kemudian PP GMKI memberi mandat kepada Caretaker BPC GMKI Padang Masa Bakti 2010 &ndash; 2012
                        dengan rentang waktu kerja <strong>Juli &ndash; September 2012</strong>. Yang dipimpin oleh:
                    </p>

                    <div class="rounded-xl border border-amber-300 bg-white p-4 shadow-2xs max-w-xl">
                        <div class="flex items-center gap-2.5 mb-3 pb-2 border-b border-slate-100">
                            <span
                                class="w-7 h-7 rounded-lg bg-amber-600 text-white text-xs font-extrabold flex items-center justify-center flex-shrink-0">
                                13
                            </span>
                            <h4 class="font-extrabold text-sm sm:text-base text-slate-900 m-0" style="color: #0f3d64;">
                                Caretaker BPC GMKI Padang Masa Bakti 2010 &ndash; 2012
                            </h4>
                        </div>
                        <div class="space-y-2 text-xs sm:text-sm">
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-5 text-slate-500 font-semibold">Ketua</div>
                                <div class="col-span-7 font-bold text-slate-800">Aristanto Tumanggor</div>
                            </div>
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-5 text-slate-500 font-semibold">Sekretaris</div>
                                <div class="col-span-7 font-semibold text-slate-700">Vernando Nainggolan</div>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-700 leading-relaxed text-justify m-0">
                        Caretaker BPC GMKI kemudian melaksanakan Maper serta menyelenggarakan <strong>KONPERCAB
                            XII</strong> pada <strong>29&ndash;30 September 2012</strong> yang memutuskan memotong masa
                        bakti menjadi <strong>1 (Satu) tahun</strong> serta memilih BPC yang baru, yang dipimpin oleh:
                    </p>
                </div>

                <!-- Daftar Periode 14 s.d. 16 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php foreach ($periodeLanjutan as $item): ?>
                        <div
                            class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs hover:border-sky-300 transition-colors">
                            <div class="flex items-center gap-2.5 mb-3 pb-2 border-b border-slate-100">
                                <span
                                    class="w-7 h-7 rounded-lg bg-[#0f3d64] text-amber-300 text-xs font-extrabold flex items-center justify-center flex-shrink-0">
                                    <?= e($item['no']) ?>
                                </span>
                                <h4 class="font-extrabold text-sm sm:text-base text-slate-900 m-0" style="color: #0f3d64;">
                                    <?= e($item['periode']) ?>
                                </h4>
                            </div>
                            <div class="space-y-2 text-xs sm:text-sm">
                                <div class="grid grid-cols-12 gap-2">
                                    <div class="col-span-4 text-slate-500 font-semibold"><?= e($item['label_ketua']) ?>
                                    </div>
                                    <div class="col-span-8 font-bold text-slate-800">
                                        <?php foreach ($item['ketua'] as $k): ?>
                                            <div><?= e($k) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-2">
                                    <div class="col-span-4 text-slate-500 font-semibold"><?= e($item['label_sekretaris']) ?>
                                    </div>
                                    <div class="col-span-8 font-semibold text-slate-700">
                                        <?php foreach ($item['sekretaris'] as $s): ?>
                                            <div><?= e($s) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 6. Penutup: Refleksi Dinamika & Aktualisasi Organisasi -->
            <div
                class="p-5 sm:p-7 rounded-2xl border-l-4 border-[#0f3d64] bg-gradient-to-r from-sky-50/70 to-slate-50 space-y-3 text-justify">
                <p class="m-0 font-medium text-slate-800">
                    Dinamika organisasi GMKI Padang memperlihatkan fluktuasi yang naik dan turun. Namun dalam
                    kemungkinan kondisi apapun, GMKI Padang selalu berupaya untuk mengaktualisasi organisasinya baik ke
                    dalam maupun keluar.
                </p>
                <p class="m-0 text-slate-700">
                    Sejak berdirinya selama hampir 27 tahun GMKI selalu berupaya untuk membangun kegiatan penguatan
                    spiritualitas kader (misalnya melalui PA dan Kebaktian Bulanan), penguatan integritas dan
                    profesionalitas kader melalui Pendidikan Kader Formal, diskusi-diskusi umum dan khusus, dan
                    pelatihan-pelatihan sebagainya. Selain itu GMKI Padang juga berupaya untuk mengaktualisasikan
                    dirinya keluar organisasi misalnya melalui penguatan hubungan dan kerjasama dengan gereja, aksi
                    partisipasi pada dunia sosial, ekonomi, budaya dan politik kemasyarakatan melalui aksi-aksi curah
                    pendapat, pembangunan jejaring sosial serta membangun opini publik.
                </p>
            </div>

        </div>
    </div>

    <!-- Visi & Misi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white animate-on-scroll"
            data-animate="fadeInLeft" style="border-top: 5px solid var(--primary);">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🎯</span>
                    <h3 class="text-xl font-bold text-slate-900" style="color: var(--primary);">Visi Organisasi</h3>
                </div>
                <p class="text-slate-700 text-sm sm:text-base leading-relaxed">
                    <?= nl2br(e($profile['visi'] ?? '')) ?>
                </p>
            </div>
        </div>

        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white animate-on-scroll"
            data-animate="fadeInRight" style="border-top: 5px solid var(--secondary);">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">⚡</span>
                    <h3 class="text-xl font-bold text-slate-900" style="color: var(--secondary);">Misi Organisasi</h3>
                </div>
                <div class="text-slate-700 text-sm sm:text-base leading-relaxed">
                    <?= nl2br(e($profile['misi'] ?? '')) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tri Panji & Panca Kegiatan Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white animate-on-scroll"
            data-animate="fadeInLeft">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🚩</span>
                    <h3 class="text-xl font-bold text-slate-900">Tri Panji GMKI</h3>
                </div>
                <div class="text-slate-700 text-sm sm:text-base leading-relaxed font-medium">
                    <?= nl2br(e($profile['tri_panji'] ?? "1. Tinggi Iman\n2. Tinggi Ilmu\n3. Tinggi Pengabdian")) ?>
                </div>
            </div>
        </div>

        <div class="card rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 bg-white animate-on-scroll"
            data-animate="fadeInRight">
            <div class="card-body p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">⭐</span>
                    <h3 class="text-xl font-bold text-slate-900">Panca Kegiatan GMKI</h3>
                </div>
                <div class="text-slate-700 text-sm sm:text-base leading-relaxed font-medium">
                    <?= nl2br(e($profile['panca_kegiatan'] ?? "1. Berdoa / Beribadah\n2. Belajar\n3. Bersaksi\n4. Bersosialisasi\n5. Berjuang")) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiga Medan Layan -->
    <div class="card rounded-3xl shadow-xl overflow-hidden mb-12 animate-on-scroll" data-animate="fadeInUp"
        style="background: linear-gradient(135deg, #071e33 0%, #0f3d64 60%, #1e5687 100%); color: #ffffff;">
        <div class="card-body p-6 sm:p-10">
            <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-300 text-center mb-8">Tiga Medan Layan GMKI</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors animate-on-scroll"
                    data-animate="fadeInUp" data-delay="100ms">
                    <div class="text-4xl mb-3">⛪</div>
                    <h4 class="text-lg font-bold text-white mb-2">Gereja</h4>
                    <p class="text-slate-200 text-xs sm:text-sm leading-relaxed text-justify">Menjadi pelayan oikoumene
                        yang mempererat persekutuan tubuh Kristus.</p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors animate-on-scroll"
                    data-animate="fadeInUp" data-delay="250ms">
                    <div class="text-4xl mb-3">🏫</div>
                    <h4 class="text-lg font-bold text-white mb-2">Perguruan Tinggi</h4>
                    <p class="text-slate-200 text-xs sm:text-sm leading-relaxed text-justify">Wadah kader intelektual
                        kritis, berprestasi, dan menjunjung kebenaran ilmiah.</p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors animate-on-scroll"
                    data-animate="fadeInUp" data-delay="400ms">
                    <div class="text-4xl mb-3">🌏</div>
                    <h4 class="text-lg font-bold text-white mb-2">Masyarakat</h4>
                    <p class="text-slate-200 text-xs sm:text-sm leading-relaxed text-justify">Garam dan terang dunia
                        yang memperjuangkan keadilan sosial dan kemanusiaan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lokasi & Peta Leaflet Pelayanan GMKI Padang -->
    <div class="card rounded-2xl shadow-md overflow-hidden bg-white border border-slate-200 animate-on-scroll"
        data-animate="fadeInUp">
        <div
            class="p-6 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xl">📍</span>
                    <h3 class="text-lg font-bold text-slate-900">Pusat Sekretariat Cabang (Sementara)</h3>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Jl. Tanah Beroyo No.2c, Belakang Tangsi, Kec. Padang Bar., kodya padang, Sumatera Barat
                </p>
            </div>
            <a href="/kontak" class="btn btn-primary btn-sm flex items-center gap-1">
                <span>Rute &amp; Kontak Lengkap</span>
                &rarr;
            </a>
        </div>
        <div id="mapProfil" style="height: 320px; width: 100%; z-index: 10;"></div>
    </div>
</div>

<!-- Script Leaflet Profil -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof L === 'undefined') return;

        const lat = -0.958567;
        const lng = 100.357547;

        const mapProfil = L.map('mapProfil', {
            center: [lat, lng],
            zoom: 17,
            scrollWheelZoom: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap | GMKI Cabang Padang',
            maxZoom: 19
        }).addTo(mapProfil);

        const marker = L.marker([lat, lng]).addTo(mapProfil);
        marker.bindPopup(`
        <div style="text-align: center; font-family: sans-serif; padding: 4px;">
            <strong style="color: #0f3d64;">BPC GMKI Cabang Padang</strong><br>
            <span style="font-size: 11px; color: #64748b;">Jl. Tanah Beroyo No.2c, Belakang Tangsi, Kec. Padang Bar., kodya padang, Sumatera Barat</span>
        </div>
    `).openPopup();
    });
</script>