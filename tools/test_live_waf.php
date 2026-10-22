<?php
$context = stream_context_create([
    'http' => [
        'ignore_errors' => true,
        'timeout' => 5
    ]
]);

// 1. Uji Permintaan Sah (Legitimate)
$normalUrl = 'http://localhost:8000/';
$normalRes = file_get_contents($normalUrl, false, $context);
$normalHdr = $http_response_header[0] ?? '';
echo "1. Akses Halaman Normal: " . $normalHdr . " -> " . (str_contains($normalHdr, '200') ? "SUKSES (Normal)" : "ERROR") . "\n";

// 2. Uji Serangan RCE
$attackUrl = 'http://localhost:8000/?q=' . urlencode('id; system("whoami")');
$attackRes = file_get_contents($attackUrl, false, $context);
$attackHdr = $http_response_header[0] ?? '';
echo "2. Akses Serangan RCE  : " . $attackHdr . " -> " . (str_contains($attackHdr, '403') ? "SUKSES DIBLOKIR WAF" : "GAGAL") . "\n";

// 3. Uji Web Shell Probing
$shellUrl = 'http://localhost:8000/?page=' . urlencode('wso.php');
$shellRes = file_get_contents($shellUrl, false, $context);
$shellHdr = $http_response_header[0] ?? '';
echo "3. Akses Probe WebShell: " . $shellHdr . " -> " . (str_contains($shellHdr, '403') ? "SUKSES DIBLOKIR WAF" : "GAGAL") . "\n";
