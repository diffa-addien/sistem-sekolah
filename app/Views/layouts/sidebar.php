<style>
    .active {
        /* tailwind color: sky */
        background-color: #00598a;
        color: #f0f9ff;
    }
</style>

<?php
$uri = \Config\Services::uri();
$segment2 = "";
$segment3 = "";
if (count($uri->getSegments()) >= 2) {
    $segment2 = $uri->getSegment(2);
}
if (count($uri->getSegments()) >= 3) {
    $segment3 = $uri->getSegment(3);
}
$role = session()->get('role');
?>

<aside class="z-20 hidden w-64 overflow-y-auto bg-white border-r border-gray-300 md:block flex-shrink-0">
    <div class="py-4 text-gray-500">
        <a class="ml-6 text-lg font-bold text-gray-800" href="#">
            Baitul Jannah
        </a>
        <ul class="mt-5 ">
            <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'dashboard' ? 'active' : '' ?>">
                <!-- Dynamic Dashboard URL -->
                <a href="<?= $role == "Wali Murid" ? base_url('wali/dashboard') : base_url('admin/dashboard') ?>"
                    class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150"
                    href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M13 12c0-.55.45-1 1-1h5c.55 0 1 .45 1 1v7c0 .55-.45 1-1 1h-5c-.55 0-1-.45-1-1v-7zm-9-7c0-.55.45-1 1-1h4c.55 0 1 .45 1 1v7c0 .55-.45 1-1 1H5c-.55 0-1-.45-1-1V5zm0 12c0-.55.45-1 1-1h4c.55 0 1 .45 1 1v2c0 .55-.45 1-1 1H5c-.55 0-1-.45-1-1v-2zm9-12c0-.55.45-1 1-1h5c.55 0 1 .45 1 1v2c0 .55-.45 1-1 1h-5c-.55 0-1-.45-1-1V5z" />
                    </svg>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
        </ul>
        <ul>
            <?php if ($role === 'Admin') : // Menu ini hanya untuk Admin 
            ?>
                <li class="relative px-6 pt-4 pb-3"
                    x-data="{ isOpen: <?= $segment2 == 'tahun-ajaran' || $segment2 == 'kelas' || $segment2 == 'siswa' || $segment2 == 'nama-kegiatan' ? 'true' : 'false' ?> }">
                    <button
                        class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                        @click="isOpen = !isOpen" aria-haspopup="true">
                        <span class="inline-flex items-center">
                            <svg class="w-5 h-5" aria-hidden="true" viewBox="0 0 32 32" fill="currentColor">
                                <path
                                    d="M7 22.01h4a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2zm18.29-1.71l-2 2a1 1 0 0 1-1.42 0 1 1 0 0 1 0-1.42l.3-.29H26a1 1 0 1 1 0-2h-3.58l.29-.29a1 1 0 0 1 1.42-1.42l2 2a1 1 0 0 1 0 1.42zM7 14.01h4a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2zm0-8h4a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2zM5 2.01c-1.64 0-3 1.36-3 3v4c0 .77.3 1.47.78 2-.48.53-.78 1.23-.78 2v4c0 .77.3 1.47.78 2-.48.53-.78 1.23-.78 2v4c0 1.64 1.36 3 3 3h13.11c1.26 1.24 2.99 2 4.89 2 3.85 0 7-3.15 7-7 0-2.78-1.64-5.19-4-6.32v-3.68c0-.77-.3-1.47-.78-2 .48-.53.78-1.23.78-2v-4c0-1.64-1.36-3-3-3zm0 2h18c.57 0 1 .43 1 1v4c0 .57-.43 1-1 1H5c-.57 0-1-.43-1-1v-4c0-.57.43-1 1-1zm0 8h18c.57 0 1 .43 1 1v3.07c-.33-.05-.66-.07-1-.07-1.9 0-3.62.76-4.89 2H5c-.57 0-1-.43-1-1v-4c0-.57.43-1 1-1zm18 6c2.77 0 5 2.23 5 5s-2.23 5-5 5c-1.44 0-2.73-.6-3.64-1.57a1 1 0 0 1-.17-.18c-.74-.86-1.19-2-1.19-3.25 0-1.26.46-2.4 1.22-3.28a1 1 0 0 1 .06-.07c.92-1.02 2.24-1.65 3.72-1.65zm-18 2h11.68c-.44.91-.68 1.93-.68 3s.24 2.09.68 3H5c-.57 0-1-.43-1-1v-4c0-.57.43-1 1-1z" />
                            </svg>
                            <span class="ml-4">Master Data</span>
                        </span>
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            :class="{'rotate-180': isOpen}">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40"
                        x-transition:leave="transition-all ease-in-out duration-300"
                        x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0"
                        class="py-2 my-2 space-y-2 overflow-hidden text-sm font-medium text-sky-950 rounded-md shadow-inner bg-sky-50"
                        aria-label="submenu">
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'tahun-ajaran' ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/tahun-ajaran') ?>" class="inline-flex w-full" href="#">Tahun Ajaran</a>
                        </li>
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'kelas' ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/kelas') ?>" class="inline-flex w-full" href="#">Data Kelas</a>
                        </li>
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'siswa' ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/siswa') ?>" class="inline-flex w-full" href="#">Data Siswa</a>
                        </li>
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'nama-kegiatan' ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/nama-kegiatan') ?>" class="inline-flex w-full" href="#">Daftar Kegiatan</a>
                        </li>
                    </ul>
                </li>
            <?php endif ?>

            <?php if ($role === 'Admin' or $role === 'Guru') : // Menu ini hanya untuk Admin dan Guru 
            ?>
                <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'kehadiran' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/kehadiran') ?>"
                        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                        href="#">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M10 21H6.2C5.0799 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V10M7 3V5M17 3V5M3 9H21M13.5 13.0001L7 13M10 17.0001L7 17M14 21L16.025 20.595C16.2015 20.5597 16.2898 20.542 16.3721 20.5097C16.4452 20.4811 16.5147 20.4439 16.579 20.399C16.6516 20.3484 16.7152 20.2848 16.8426 20.1574L21 16C21.5523 15.4477 21.5523 14.5523 21 14C20.4477 13.4477 19.5523 13.4477 19 14L14.8426 18.1574C14.7152 18.2848 14.6516 18.3484 14.601 18.421C14.5561 18.4853 14.5189 18.5548 14.4903 18.6279C14.458 18.7102 14.4403 18.7985 14.405 18.975L14 21Z" />
                        </svg>
                        <span class="ml-4">Kehadiran</span>
                    </a>
                </li>
            <?php endif ?>

            <?php if ($role === 'Admin') : // Menu ini hanya untuk Admin 
            ?>
                <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'kegiatan' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/kegiatan') ?>"
                        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                        href="#">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M2 5.5L3.21429 7L7.5 3 M2 12.5L3.21429 14L7.5 10 M2 19.5L3.21429 21L7.5 17 M22 19L12 19 M22 12L12 12 M22 5L12 5" />
                        </svg>
                        <span class="ml-4">Catatan Kegiatan</span>
                    </a>
                </li>
            <?php endif ?>

            <?php if ($role === 'Admin') : // Menu ini hanya untuk Admin 
            ?>
                <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'kenaikan-kelas' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/kenaikan-kelas') ?>"
                        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                        href="#">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M21 21H6.2C5.07989 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V3M7 15L12 9L16 13L21 7" />
                        </svg>
                        <span class="ml-4">Kenaikan Kelas</span>
                    </a>
                </li>
            <?php endif ?>

            <?php if ($role === 'Admin') : ?>
                <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'laporan' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/laporan/kehadiran') ?>"
                        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800">
                        <svg class="w-5 h-5" aria-hidden="true" viewBox="0 0 297.001 297.001" fill="currentColor">
                                <path
                                    d="M178.138 152.609c-29.565 0-53.617 24.052-53.617 53.617s24.052 53.617 53.617 53.617 53.618-24.052 53.618-53.617S207.701 152.609 178.138 152.609zm0 87.584c-18.729 0-33.967-15.238-33.967-33.967 0-5.797 1.463-11.258 4.034-16.037l22.985 22.985c1.843 1.842 4.342 2.878 6.948 2.878h32.513c-1.726 10.189-10.699 20.378-25.013 20.378zM182.208 196.401L162.1 176.293c4.78-2.571 10.241-4.034 16.037-4.034 15.315 0 28.289 10.189 32.514 24.142h-28.451z M235.993 29.194h-28.268c-3.455-8.548-11.835-14.598-21.606-14.598H170.86C167.071 6.009 158.475 0 148.501 0s-18.57 6.009-22.359 14.597h-15.259c-9.771 0-18.152 6.05-21.606 14.598H61.009c-13.517 0-24.515 10.998-24.515 24.515v218.777c0 13.517 10.998 24.514 24.515 24.514h174.984c13.517 0 24.515-10.997 24.515-24.514V53.709c0-13.517-10.997-24.515-24.515-24.515zM107.234 37.895v0c.001-2.011 1.638-3.648 3.649-3.648h23.02c5.426 0 9.825-4.399 9.825-9.825 0-2.631 2.141-4.772 4.772-4.772s4.772 2.141 4.772 4.772c0 5.426 4.399 9.825 9.825 9.825h23.02c2.011 0 3.648 1.637 3.648 3.648v16.847c0 2.011-1.637 3.648-3.648 3.648h-75.235c-2.011 0-3.648-1.637-3.648-3.648v-16.847zM235.993 277.35H61.009c-2.683 0-4.865-2.182-4.865-4.864V53.709c0-2.683 2.182-4.865 4.865-4.865h26.576v5.897c0 12.847 10.452 23.298 23.298 23.298h75.235c12.847 0 23.298-10.452 23.298-23.298v-5.897h26.576c2.683 0 4.865 2.182 4.865 4.865v218.777h.001c-4.864 2.182-2.682 2.182-2.682 2.182z M75.514 113.869h72.987c5.426 0 9.825-4.399 9.825-9.825 0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825 0 5.426 4.399 5.426 9.826 5.426z M75.514 143.064h43.793c5.426 0 9.825-4.399 9.825-9.825 0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825 0 5.426 4.399 5.426 9.826 5.426z M129.131 162.434c0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825s4.399 9.825 9.825 9.825h43.793c5.427 0 9.826-4.399 9.826-9.825z" />
                            </svg>
                        <span class="ml-4">Laporan</span>
                    </a>
                </li>
            <?php endif ?>

            <?php if ($role === 'Wali Murid') : ?>
                <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'laporan-siswa' ? 'active' : '' ?>">
                    <a href="<?= base_url('wali/laporan-siswa') ?>"
                        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800">
                        <svg class="w-5 h-5" aria-hidden="true" viewBox="0 0 297.001 297.001" fill="currentColor">
                                <path
                                    d="M178.138 152.609c-29.565 0-53.617 24.052-53.617 53.617s24.052 53.617 53.617 53.617 53.618-24.052 53.618-53.617S207.701 152.609 178.138 152.609zm0 87.584c-18.729 0-33.967-15.238-33.967-33.967 0-5.797 1.463-11.258 4.034-16.037l22.985 22.985c1.843 1.842 4.342 2.878 6.948 2.878h32.513c-1.726 10.189-10.699 20.378-25.013 20.378zM182.208 196.401L162.1 176.293c4.78-2.571 10.241-4.034 16.037-4.034 15.315 0 28.289 10.189 32.514 24.142h-28.451z M235.993 29.194h-28.268c-3.455-8.548-11.835-14.598-21.606-14.598H170.86C167.071 6.009 158.475 0 148.501 0s-18.57 6.009-22.359 14.597h-15.259c-9.771 0-18.152 6.05-21.606 14.598H61.009c-13.517 0-24.515 10.998-24.515 24.515v218.777c0 13.517 10.998 24.514 24.515 24.514h174.984c13.517 0 24.515-10.997 24.515-24.514V53.709c0-13.517-10.997-24.515-24.515-24.515zM107.234 37.895v0c.001-2.011 1.638-3.648 3.649-3.648h23.02c5.426 0 9.825-4.399 9.825-9.825 0-2.631 2.141-4.772 4.772-4.772s4.772 2.141 4.772 4.772c0 5.426 4.399 9.825 9.825 9.825h23.02c2.011 0 3.648 1.637 3.648 3.648v16.847c0 2.011-1.637 3.648-3.648 3.648h-75.235c-2.011 0-3.648-1.637-3.648-3.648v-16.847zM235.993 277.35H61.009c-2.683 0-4.865-2.182-4.865-4.864V53.709c0-2.683 2.182-4.865 4.865-4.865h26.576v5.897c0 12.847 10.452 23.298 23.298 23.298h75.235c12.847 0 23.298-10.452 23.298-23.298v-5.897h26.576c2.683 0 4.865 2.182 4.865 4.865v218.777h.001c-4.864 2.182-2.682 2.182-2.682 2.182z M75.514 113.869h72.987c5.426 0 9.825-4.399 9.825-9.825 0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825 0 5.426 4.399 5.426 9.826 5.426z M75.514 143.064h43.793c5.426 0 9.825-4.399 9.825-9.825 0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825 0 5.426 4.399 5.426 9.826 5.426z M129.131 162.434c0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825s4.399 9.825 9.825 9.825h43.793c5.427 0 9.826-4.399 9.826-9.825z" />
                            </svg>
                        <span class="ml-4">Laporan Siswa</span>
                    </a>
                </li>
            <?php endif ?>

            <?php if ($role === 'Admin') : // Menu ini hanya untuk Admin 
            ?>
                <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'user' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/user') ?>"
                        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M1.5 6.5C1.5 3.46243 3.96243 1 7 1C10.0376 1 12.5 3.46243 12.5 6.5C12.5 9.53757 10.0376 12 7 12C3.96243 12 1.5 9.53757 1.5 6.5Z
                 M14.4999 6.5C14.4999 8.00034 14.0593 9.39779 13.3005 10.57C14.2774 11.4585 15.5754 12 16.9999 12C20.0375 12 22.4999 9.53757 22.4999 6.5C22.4999 3.46243 20.0375 1 16.9999 1C15.5754 1 14.2774 1.54153 13.3005 2.42996C14.0593 3.60221 14.4999 4.99966 14.4999 6.5Z
                 M0 18C0 15.7909 1.79086 14 4 14H10C12.2091 14 14 15.7909 14 18V22C14 22.5523 13.5523 23 13 23H1C0.447716 23 0 22.5523 0 22V18Z
                 M16 18V23H23C23.5522 23 24 22.5523 24 22V18C24 15.7909 22.2091 14 20 14H14.4722C15.4222 15.0615 16 16.4633 16 18Z" />
                        </svg>
                        <span class="ml-4">Manajemen Pengguna</span>
                    </a>

                </li>
            <?php endif ?>

        </ul>
    </div>
</aside>

<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="isSideMenuOpen = false"
    class="fixed inset-y-0 left-0 right-0 z-20 mt-16 bg-black bg-opacity-50">
</div>

<aside class="fixed inset-y-0 z-30 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white md:hidden" x-show="isSideMenuOpen"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20">
    <div class="py-4 text-gray-500">
        <a class="ml-6 text-lg font-bold text-gray-800" href="#">
            Baitul Jannah
        </a>
        <ul class="mt-5 ">
            <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'dashboard' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/dashboard') ?>"
                    class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150"
                    href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M13 12c0-.55.45-1 1-1h5c.55 0 1 .45 1 1v7c0 .55-.45 1-1 1h-5c-.55 0-1-.45-1-1v-7zm-9-7c0-.55.45-1 1-1h4c.55 0 1 .45 1 1v7c0 .55-.45 1-1 1H5c-.55 0-1-.45-1-1V5zm0 12c0-.55.45-1 1-1h4c.55 0 1 .45 1 1v2c0 .55-.45 1-1 1H5c-.55 0-1-.45-1-1v-2zm9-12c0-.55.45-1 1-1h5c.55 0 1 .45 1 1v2c0 .55-.45 1-1 1h-5c-.55 0-1-.45-1-1V5z" />
                    </svg>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
        </ul>
        <ul>
            <li class="relative px-6 pt-4 pb-3"
                x-data="{ isOpen: <?= $segment2 == 'tahun-ajaran' || $segment2 == 'kelas' || $segment2 == 'siswa' || $segment2 == 'nama-kegiatan' ? 'true' : 'false' ?> }">
                <button
                    class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                    @click="isOpen = !isOpen" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5" aria-hidden="true" viewBox="0 0 32 32" fill="currentColor">
                            <path
                                d="M7 22.01h4a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2zm18.29-1.71l-2 2a1 1 0 0 1-1.42 0 1 1 0 0 1 0-1.42l.3-.29H26a1 1 0 1 1 0-2h-3.58l.29-.29a1 1 0 0 1 1.42-1.42l2 2a1 1 0 0 1 0 1.42zM7 14.01h4a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2zm0-8h4a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2zM5 2.01c-1.64 0-3 1.36-3 3v4c0 .77.3 1.47.78 2-.48.53-.78 1.23-.78 2v4c0 .77.3 1.47.78 2-.48.53-.78 1.23-.78 2v4c0 1.64 1.36 3 3 3h13.11c1.26 1.24 2.99 2 4.89 2 3.85 0 7-3.15 7-7 0-2.78-1.64-5.19-4-6.32v-3.68c0-.77-.3-1.47-.78-2 .48-.53.78-1.23.78-2v-4c0-1.64-1.36-3-3-3zm0 2h18c.57 0 1 .43 1 1v4c0 .57-.43 1-1 1H5c-.57 0-1-.43-1-1v-4c0-.57.43-1 1-1zm0 8h18c.57 0 1 .43 1 1v3.07c-.33-.05-.66-.07-1-.07-1.9 0-3.62.76-4.89 2H5c-.57 0-1-.43-1-1v-4c0-.57.43-1 1-1zm18 6c2.77 0 5 2.23 5 5s-2.23 5-5 5c-1.44 0-2.73-.6-3.64-1.57a1 1 0 0 1-.17-.18c-.74-.86-1.19-2-1.19-3.25 0-1.26.46-2.4 1.22-3.28a1 1 0 0 1 .06-.07c.92-1.02 2.24-1.65 3.72-1.65zm-18 2h11.68c-.44.91-.68 1.93-.68 3s.24 2.09.68 3H5c-.57 0-1-.43-1-1v-4c0-.57.43-1 1-1z" />
                        </svg>
                        <span class="ml-4">Master Data</span>
                    </span>
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        :class="{'rotate-180': isOpen}">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40"
                    x-transition:leave="transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0"
                    class="py-2 my-2 space-y-2 overflow-hidden text-sm font-medium text-sky-950 rounded-md shadow-inner bg-sky-50"
                    aria-label="submenu">
                    <li
                        class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'tahun-ajaran' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/tahun-ajaran') ?>" class="w-full" href="#">Tahun Ajaran</a>
                    </li>
                    <li
                        class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'kelas' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/kelas') ?>" class="w-full" href="#">Data Kelas</a>
                    </li>
                    <li
                        class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'siswa' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/siswa') ?>" class="w-full" href="#">Data Siswa</a>
                    </li>
                    <li
                        class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 <?= $segment2 == 'nama-kegiatan' ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/nama-kegiatan') ?>" class="w-full" href="#">Daftar Kegiatan</a>
                    </li>
                </ul>
            </li>

            <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'kehadiran' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/kehadiran') ?>"
                    class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                    href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M10 21H6.2C5.0799 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V10M7 3V5M17 3V5M3 9H21M13.5 13.0001L7 13M10 17.0001L7 17M14 21L16.025 20.595C16.2015 20.5597 16.2898 20.542 16.3721 20.5097C16.4452 20.4811 16.5147 20.4439 16.579 20.399C16.6516 20.3484 16.7152 20.2848 16.8426 20.1574L21 16C21.5523 15.4477 21.5523 14.5523 21 14C20.4477 13.4477 19.5523 13.4477 19 14L14.8426 18.1574C14.7152 18.2848 14.6516 18.3484 14.601 18.421C14.5561 18.4853 14.5189 18.5548 14.4903 18.6279C14.458 18.7102 14.4403 18.7985 14.405 18.975L14 21Z" />
                    </svg>
                    <span class="ml-4">Kehadiran</span>
                </a>
            </li>

            <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'kegiatan' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/kegiatan') ?>"
                    class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                    href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M2 5.5L3.21429 7L7.5 3 M2 12.5L3.21429 14L7.5 10 M2 19.5L3.21429 21L7.5 17 M22 19L12 19 M22 12L12 12 M22 5L12 5" />
                    </svg>
                    <span class="ml-4">Catatan Kegiatan</span>
                </a>
            </li>

            <li class="relative px-6 pt-4 pb-3 <?= $segment2 == 'kenaikan-kelas' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/kenaikan-kelas') ?>"
                    class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                    href="#">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M21 21H6.2C5.07989 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V3M7 15L12 9L16 13L21 7" />
                    </svg>
                    <span class="ml-4">Kenaikan Kelas</span>
                </a>
            </li>

            <li class="relative px-6 pt-4 pb-3" x-data="{ isOpen: <?= $segment2 == 'laporan' ? 'true' : 'false' ?> }">
                <button
                    class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                    @click="isOpen = !isOpen" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5" aria-hidden="true" viewBox="0 0 297.001 297.001" fill="currentColor">
                            <path
                                d="M178.138 152.609c-29.565 0-53.617 24.052-53.617 53.617s24.052 53.617 53.617 53.617 53.618-24.052 53.618-53.617S207.701 152.609 178.138 152.609zm0 87.584c-18.729 0-33.967-15.238-33.967-33.967 0-5.797 1.463-11.258 4.034-16.037l22.985 22.985c1.843 1.842 4.342 2.878 6.948 2.878h32.513c-1.726 10.189-10.699 20.378-25.013 20.378zM182.208 196.401L162.1 176.293c4.78-2.571 10.241-4.034 16.037-4.034 15.315 0 28.289 10.189 32.514 24.142h-28.451z M235.993 29.194h-28.268c-3.455-8.548-11.835-14.598-21.606-14.598H170.86C167.071 6.009 158.475 0 148.501 0s-18.57 6.009-22.359 14.597h-15.259c-9.771 0-18.152 6.05-21.606 14.598H61.009c-13.517 0-24.515 10.998-24.515 24.515v218.777c0 13.517 10.998 24.514 24.515 24.514h174.984c13.517 0 24.515-10.997 24.515-24.514V53.709c0-13.517-10.997-24.515-24.515-24.515zM107.234 37.895v0c.001-2.011 1.638-3.648 3.649-3.648h23.02c5.426 0 9.825-4.399 9.825-9.825 0-2.631 2.141-4.772 4.772-4.772s4.772 2.141 4.772 4.772c0 5.426 4.399 9.825 9.825 9.825h23.02c2.011 0 3.648 1.637 3.648 3.648v16.847c0 2.011-1.637 3.648-3.648 3.648h-75.235c-2.011 0-3.648-1.637-3.648-3.648v-16.847zM235.993 277.35H61.009c-2.683 0-4.865-2.182-4.865-4.864V53.709c0-2.683 2.182-4.865 4.865-4.865h26.576v5.897c0 12.847 10.452 23.298 23.298 23.298h75.235c12.847 0 23.298-10.452 23.298-23.298v-5.897h26.576c2.683 0 4.865 2.182 4.865 4.865v218.777h.001c-4.864 2.182-2.682 2.182-2.682 2.182z M75.514 113.869h72.987c5.426 0 9.825-4.399 9.825-9.825 0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825 0 5.426 4.399 5.426 9.826 5.426z M75.514 143.064h43.793c5.426 0 9.825-4.399 9.825-9.825 0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825 0 5.426 4.399 5.426 9.826 5.426z M129.131 162.434c0-5.426-4.399-9.825-9.825-9.825H75.514c-5.426 0-9.825 4.399-9.825 9.825s4.399 9.825 9.825 9.825h43.793c5.427 0 9.826-4.399 9.826-9.825z" />
                        </svg>
                        <span class="ml-4">Laporan</span>
                    </span>
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        :class="{'rotate-180': isOpen}">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul x-show="isOpen" x-transition:enter="transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-40"
                    x-transition:leave="transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 max-h-40" x-transition:leave-end="opacity-0 max-h-0"
                    class="p-2 my-2 space-y-2 overflow-hidden text-sm font-medium text-sky-950 rounded-md shadow-inner bg-sky-50"
                    aria-label="submenu">
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?= base_url('admin/laporan/kehadiran') ?>" class="w-full" href="#">Kehadiran</a>
                    </li>
                    <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800">
                        <a href="<?= base_url('') ?>" class="w-full" href="#">Kegiatan</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>