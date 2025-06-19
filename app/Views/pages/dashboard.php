<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 xl:grid-cols-4">
    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.124-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.124-1.283.356-1.857m0 0a3.002 3.002 0 015.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Customers</p>
                <p class="text-2xl font-bold text-gray-800">3,782</p>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <span class="text-sm font-semibold text-green-600">↑ 11.01%</span>
        </div>
    </div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-3 bg-indigo-100 rounded-full">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Orders</p>
                <p class="text-2xl font-bold text-gray-800">5,359</p>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <span class="text-sm font-semibold text-red-600">↓ 9.05%</span>
        </div>
    </div>
    <div class="p-6 bg-white rounded-lg shadow-md md:col-span-2 xl:col-span-2">
        <h3 class="font-semibold text-gray-700">Monthly Sales</h3>
        <div id="bar-chart"></div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="p-6 bg-white rounded-lg shadow-md lg:col-span-1">
        <h3 class="font-semibold text-gray-700">Monthly Target</h3>
        <p class="text-sm text-gray-500">Target you've set for each month</p>
        <div id="radial-chart" class="flex justify-center"></div>
        <p class="mt-4 text-sm text-center text-gray-600">
            You earn $3287 today, it's higher than last month. Keep up your good work!
        </p>
        <div class="flex justify-between mt-6 text-center">
            <div>
                <p class="text-sm text-gray-500">Target</p>
                <p class="text-lg font-bold text-gray-800">$20K <span class="text-red-500">↓</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Revenue</p>
                <p class="text-lg font-bold text-gray-800">$20K <span class="text-green-500">↑</span></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Today</p>
                <p class="text-lg font-bold text-gray-800">$20K <span class="text-green-500">↑</span></p>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
         <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="font-semibold text-gray-700">Data Siswa Aktif</h3>
            <p class="mt-4 text-gray-600">Area ini bisa digunakan untuk menampilkan data penting lainnya, seperti daftar siswa yang baru mendaftar, dll.</p>
         </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- Bar Chart (Monthly Sales) ---
        var barChartOptions = {
            series: [{
                name: 'Sales',
                data: [160, 380, 200, 290, 180, 240, 100, 210, 390, 280, 100]
            }],
            chart: {
                height: 350,
                type: 'bar',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
        };

        var barChart = new ApexCharts(document.querySelector("#bar-chart"), barChartOptions);
        barChart.render();

        // --- Radial Chart (Monthly Target) ---
        var radialChartOptions = {
            series: [75.55],
            chart: {
                height: 250,
                type: 'radialBar',
                toolbar: { show: false }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    hollow: {
                        margin: 0,
                        size: '70%',
                        background: '#fff',
                    },
                    track: {
                        background: '#eee',
                        strokeWidth: '67%',
                        margin: 0,
                    },
                    dataLabels: {
                        show: true,
                        name: {
                            show: false,
                        },
                        value: {
                            formatter: function (val) {
                                return val + "%";
                            },
                            color: '#111',
                            fontSize: '36px',
                            show: true,
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#ABE5A1'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            stroke: {
                lineCap: 'round'
            },
            labels: ['Percent'],
        };

        var radialChart = new ApexCharts(document.querySelector("#radial-chart"), radialChartOptions);
        radialChart.render();
    });
</script>


<?= $this->endSection() ?>