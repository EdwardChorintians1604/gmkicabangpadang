/**
 * GMKI Cabang Padang - Chart.js Statistics Module
 * statistik.js
 */

document.addEventListener('DOMContentLoaded', function () {
    // Periksa apakah Chart library telah dimuat
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js belum dimuat.');
        return;
    }

    // Default styling for charts
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Chart Persebaran Komisariat
    const ctxKomisariat = document.getElementById('chartKomisariat');
    if (ctxKomisariat && window.chartDataPayload?.komisariat) {
        new Chart(ctxKomisariat, {
            type: 'bar',
            data: {
                labels: window.chartDataPayload.komisariat.labels,
                datasets: [{
                    label: 'Jumlah Civitas',
                    data: window.chartDataPayload.komisariat.data,
                    backgroundColor: '#1e5687',
                    hoverBackgroundColor: '#0f3d64',
                    borderRadius: 8,
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    }

    // 2. Chart Rasio Gender (L / P)
    const ctxGender = document.getElementById('chartGender');
    if (ctxGender && window.chartDataPayload?.gender) {
        new Chart(ctxGender, {
            type: 'doughnut',
            data: {
                labels: window.chartDataPayload.gender.labels,
                datasets: [{
                    data: window.chartDataPayload.gender.data,
                    backgroundColor: ['#0f3d64', '#f59e0b'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 18,
                            usePointStyle: true,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    // 3. Chart Tren Tahun Maperca
    const ctxMaperca = document.getElementById('chartMaperca');
    if (ctxMaperca && window.chartDataPayload?.tahun_maperca) {
        new Chart(ctxMaperca, {
            type: 'line',
            data: {
                labels: window.chartDataPayload.tahun_maperca.labels,
                datasets: [{
                    label: 'Kader Masuk',
                    data: window.chartDataPayload.tahun_maperca.data,
                    borderColor: '#0f3d64',
                    backgroundColor: 'rgba(15, 61, 100, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#0f3d64',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    }

    // 4. Chart Perguruan Tinggi
    const ctxPT = document.getElementById('chartPT');
    if (ctxPT && window.chartDataPayload?.perguruan_tinggi) {
        new Chart(ctxPT, {
            type: 'bar',
            data: {
                labels: window.chartDataPayload.perguruan_tinggi.labels,
                datasets: [{
                    label: 'Mahasiswa',
                    data: window.chartDataPayload.perguruan_tinggi.data,
                    backgroundColor: '#d97706',
                    hoverBackgroundColor: '#b45309',
                    borderRadius: 8,
                    maxBarThickness: 32
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: '#f1f5f9' }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
