<aside class="z-20 hidden w-64 overflow-y-auto bg-white md:block flex-shrink-0">
    <div class="py-4 text-gray-500">
        <a class="ml-6 text-lg font-bold text-gray-800" href="#">
            Baitul Jannah
        </a>
        <ul class="mt-6">
            <li class="relative px-6 py-3">
                <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
                <a class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800" href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
        </ul>
        <ul>
            <li class="relative px-6 py-3" x-data="{ isOpen: false }">
                <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" @click="isOpen = !isOpen" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span class="ml-4">Master Data</span>
                    </span>
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': isOpen}">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50" aria-label="submenu">
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?=base_url('admin/tahun-ajaran')?>" class="w-full" href="#">Tahun Ajaran</a>
                    </li>
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?=base_url('admin/kelas')?>" class="w-full" href="#">Data Kelas</a>
                    </li>
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?=base_url('admin/siswa')?>" class="w-full" href="#">Data Siswa</a>
                    </li>
                </ul>
            </li>

            <li class="relative px-6 py-3">
                <a href="<?=base_url('admin/kehadiran')?>" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="ml-4">Kehadiran</span>
                </a>
            </li>

            <li class="relative px-6 py-3" x-data="{ isOpen: false }">
                <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" @click="isOpen = !isOpen" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <!-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01V5M12 20v-1m0 1v.01M12 18v-1m0-1v-1m0-1v-1m0-1v-1m0-1v-1m-4-1.225c-.753.25-1.427.604-2 .998M8 11.775c.573-.394 1.247-.748 2-1M8 9.225c.573-.394 1.247-.748 2-1m-2 .002V8.225M16 11.775c-.573-.394-1.247-.748-2-1m2-2.55c-.573-.394-1.247-.748-2-1m2 .002V8.225m4 3.55c.753.25 1.427.604 2 .998m-2-2.55c.573-.394 1.247-.748 2-1m0 0V8.225"></path>
                        </svg> -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 30 32" xmlns="http://www.w3.org/2000/svg">
<style type="text/css">
	.st0{fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
</style>
<g>
	<path class="st0" d="M24,7c0-0.1,0-0.1,0-0.2l-0.7-4.3C23,1.1,21.8,0,20.3,0h-8.6c-1.5,0-2.7,1.1-3,2.5L8,6.8C8,6.9,8,6.9,8,7c-1.2,0.9-2,2.4-2,4v10c0,1.6,0.8,3.1,2,4c0,0.1,0,0.1,0,0.2l0.7,4.3c0.2,1.5,1.5,2.5,3,2.5h8.6c1.5,0,2.7-1.1,3-2.5l0.7-4.3c0-0.1,0-0.1,0-0.2c1.2-0.9,2-2.4,2-4V11C26,9.4,25.2,7.9,24,7z M24,21c0,1.7-1.3,3-3,3H11c-1.7,0-3-1.3-3-3V11c0-1.7,1.3-3,3-3h10c1.7,0,3,1.3,3,3V21z"/>
	<path class="st0" d="M21,15h-1.4l-1.7-3.4C17.7,11.2,17.4,11,17,11c-0.4,0-0.7,0.2-0.9,0.6L13.3,18H11c-0.6,0-1,0.4-1,1s0.4,1,1,1h3c0.4,0,0.8-0.2,0.9-0.6l2.2-5l1,2.1c0.2,0.3,0.5,0.6,0.9,0.6h2c0.6,0,1-0.4,1-1S21.6,15,21,15z"/>
</g>
</svg>
                        <span class="ml-4">Kegiatan</span>
                    </span>
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': isOpen}">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50" aria-label="submenu">
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?=base_url('admin/nama-kegiatan')?>" class="w-full" href="#">Daftar Kegiatan</a>
                    </li>
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?=base_url('admin/kegiatan')?>" class="w-full" href="#">Catatan Kegiatan</a>
                    </li>
                </ul>
            </li>

            <li class="relative px-6 py-3" x-data="{ isOpen: false }">
                <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" @click="isOpen = !isOpen" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01V5M12 20v-1m0 1v.01M12 18v-1m0-1v-1m0-1v-1m0-1v-1m0-1v-1m-4-1.225c-.753.25-1.427.604-2 .998M8 11.775c.573-.394 1.247-.748 2-1M8 9.225c.573-.394 1.247-.748 2-1m-2 .002V8.225M16 11.775c-.573-.394-1.247-.748-2-1m2-2.55c-.573-.394-1.247-.748-2-1m2 .002V8.225m4 3.55c.753.25 1.427.604 2 .998m-2-2.55c.573-.394 1.247-.748 2-1m0 0V8.225"></path>
                        </svg>
                        <span class="ml-4">Laporan</span>
                    </span>
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': isOpen}">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50" aria-label="submenu">
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?=base_url('admin/laporan/kehadiran')?>" class="w-full" href="#">Kehadiran</a>
                    </li>
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?=base_url('')?>" class="w-full" href="#">Kegiatan</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>

<div x-show="isSideMenuOpen"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="isSideMenuOpen = false"
    class="fixed inset-y-0 left-0 right-0 z-20 mt-16 bg-black bg-opacity-50">
</div>

<aside class="fixed inset-y-0 z-30 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white md:hidden"
    x-show="isSideMenuOpen"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20">
    <div class="py-4 text-gray-500">
        <a class="ml-6 text-lg font-bold text-gray-800" href="#">
            Baitul Jannah
        </a>
        <ul class="mt-6">
            <li class="relative px-6 py-3">
                <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
        </ul>
        <ul>
            <li class="relative px-6 py-3" x-data="{ isOpen: false }">
                <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" @click="isOpen = !isOpen" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span class="ml-4">Master Data</span>
                    </span>
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': isOpen}">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50" aria-label="submenu">
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a class="w-full" href="#">Data Siswa</a>
                    </li>
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a class="w-full" href="#">Data Kelas</a>
                    </li>
                </ul>
            </li>

            <li class="relative px-6 py-3">
                <a href="<?=base_url('admin/kehadiran')?>" class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="ml-4">Presensi Kehadiran</span>
                </a>
            </li>

            <li class="relative px-6 py-3" x-data="{ isOpen: false }">
                <button class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800" @click="isOpen = !isOpen" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01V5M12 20v-1m0 1v.01M12 18v-1m0-1v-1m0-1v-1m0-1v-1m0-1v-1m-4-1.225c-.753.25-1.427.604-2 .998M8 11.775c.573-.394 1.247-.748 2-1M8 9.225c.573-.394 1.247-.748 2-1m-2 .002V8.225M16 11.775c-.573-.394-1.247-.748-2-1m2-2.55c-.573-.394-1.247-.748-2-1m2 .002V8.225m4 3.55c.753.25 1.427.604 2 .998m-2-2.55c.573-.394 1.247-.748 2-1m0 0V8.225"></path>
                        </svg>
                        <span class="ml-4">Laporan</span>
                    </span>
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': isOpen}">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50" aria-label="submenu">
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a class="w-full" href="#">Presensi</a>
                    </li>
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a class="w-full" href="#">Kegiatan</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>