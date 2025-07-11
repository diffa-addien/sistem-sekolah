<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>
Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="grid gap-6 mb-8 md:grid-cols-5">
    <div class="order-2 md:order-1 md:col-span-3 grid gap-6 lg:grid-cols-2">
        <div class="flex items-center p-4 bg-white rounded-2xl border border-gray-300 shadow-xs">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">
                    Total Siswa (Aktif)
                </p>
                <p class="text-lg font-semibold text-gray-700">
                    <?= esc($stats['total_students']) ?>
                </p>
            </div>
        </div>
        <div class="flex items-center p-4 bg-white rounded-2xl border border-gray-300 shadow-xs">
            <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">
                    Wali Murid Terdaftar
                </p>
                <p class="text-lg font-semibold text-gray-700">
                    <?= esc($stats['total_parents']) ?>
                </p>
            </div>
        </div>
        <div class="col-span-2 grid grid-cols-2 bg-white rounded-2xl border border-gray-300 shadow-xs">
            <div class="flex items-center p-4">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-xl">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">
                        Jumlah Guru
                    </p>
                    <p class="text-lg font-semibold text-gray-700">
                        <?= esc($stats['total_teachers']) ?>
                    </p>
                </div>
            </div>
            <div class="flex items-center p-4">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-xl">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">
                        Jumlah Kelas (Aktif)
                    </p>
                    <p class="text-lg font-semibold text-gray-700">
                        <?= esc($stats['total_classes']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="order-1 md:order-2 md:col-span-2 flex flex-col items-between justify-between bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-xl text-gray-800">Dashboard</h1>
                <p class="text-sm italic text-gray-400">Sistem Informasi v1.00</p>
            </div>
            <div class="flex items-center space-x-4 text-sm text-gray-500">
                <a href="#" class="inline-flex items-center hover:text-gray-700">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 19V5a2 2 0 012-2h10a2 2 0 012 2v14M4 19h16M9 10h6" />
                    </svg>
                    Doc<span class="hidden sm:block">umentation</span>
                </a>
            </div>
        </div>
        <div class="my-4 flex items-center justify-center">
            <div class="text-gray-600">
                <div class="font-bold text-xl text-center" id="realtime-clock"></div>
                <div class="text-sm text-center" id="realtime-date"></div>
            </div>
        </div>
        <hr class="border-gray-200" />
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const timeOptions = { timeZone: 'Asia/Jakarta', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const dateOptions = { timeZone: 'Asia/Jakarta', year: 'numeric', month: 'long', day: 'numeric' };
            const timeString = now.toLocaleTimeString('id-ID', timeOptions);
            const dateString = now.toLocaleDateString('id-ID', dateOptions);
            document.getElementById('realtime-clock').textContent = timeString.replace(/[.]/g, ':');
            document.getElementById('realtime-date').textContent = dateString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</div>

<div class="min-w-0 p-4 bg-white rounded-2xl border border-gray-300 shadow-xs">
    <h4 class="mb-4 font-semibold text-gray-800">
        Grafik Kehadiran (7 Hari Terakhir)
    </h4>
    <div id="attendanceChart"></div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chartData = <?= json_encode($attendanceChart) ?>;

        var options = {
            series: chartData.series,
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4
                },
            },
            xaxis: {
                categories: chartData.categories,
            },
            legend: {
                position: 'top',
                offsetY: 0
            },
            fill: {
                opacity: 1
            },
            colors: ['#4CAF50', '#FFC107', '#2196F3'] // Warna untuk Hadir, Sakit, Izin
        };

        var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
        chart.render();
    });
</script>
<?= $this->endSection() ?>