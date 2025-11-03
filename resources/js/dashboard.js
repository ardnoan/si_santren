import Chart from 'chart.js/auto';

document.addEventListener("DOMContentLoaded", () => {
    const santriData = window.santriPerKelas || [];
    const kelasLabels = santriData.map(item => item.kelas);
    const kelasValues = santriData.map(item => item.jumlah);

    if (kelasLabels.length > 0) {
        new Chart(document.getElementById('santriPerKelasChart'), {
            type: 'bar',
            data: {
                labels: kelasLabels,
                datasets: [{
                    label: 'Jumlah Santri',
                    data: kelasValues,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    }

    const kehadiranData = window.kehadiranToday || { hadir: 0, izin: 0, sakit: 0, alpa: 0 };
    const totalKehadiran = (kehadiranData.hadir || 0) + (kehadiranData.izin || 0) + (kehadiranData.sakit || 0) + (kehadiranData.alpa || 0);

    if (totalKehadiran > 0) {
        new Chart(document.getElementById('kehadiranChart'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                datasets: [{
                    data: [
                        kehadiranData.hadir || 0,
                        kehadiranData.izin || 0,
                        kehadiranData.sakit || 0,
                        kehadiranData.alpa || 0
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
