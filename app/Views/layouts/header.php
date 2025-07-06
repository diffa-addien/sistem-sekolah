<header class="z-30 md:z-10 py-4 bg-white border-b border-gray-300">
    <div class="container flex items-center justify-between md:justify-end h-full px-6 mx-auto text-purple-600">
        <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple" x-on:click="isSideMenuOpen = !isSideMenuOpen" aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>

        <ul class="flex items-center flex-shrink-0 space-x-6">
            <li class="relative" x-data="{ isProfileMenuOpen: false }">
                <button class="flex items-center gap-2 align-middle rounded-lg focus:shadow-outline-purple focus:outline-none" x-on:click="isProfileMenuOpen = !isProfileMenuOpen" x-on:keydown.escape="isProfileMenuOpen = false" aria-label="Account" aria-haspopup="true">
                    <span class="hidden md:inline text-sm font-medium text-gray-700">
                        <?= esc(session()->get('name')) ?>
                    </span>
                    <img class="object-cover w-8 h-8 rounded-full"
                        src="<?= base_url('Uploads/photos/' . (session()->get('photo') ?? 'default.png')) ?>"
                        alt="Foto Profil" aria-hidden="true">
                </button>

                <ul x-show="isProfileMenuOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    x-on:click.away="isProfileMenuOpen = false"
                    class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md"
                    aria-label="submenu">
                    <li class="px-2 py-1">
                        <p class="font-semibold"><?= esc(session()->get('username')) ?></p>
                        <p class="text-xs text-gray-500"><?= esc(session()->get('role')) ?></p>
                    </li>
                    <li class="flex">
                        <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800" href="<?= site_url('akun') ?>">
                            <svg class="w-4 h-4 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12.12 12.78C12.05 12.77 11.96 12.77 11.88 12.78C10.12 12.72 8.71997 11.28 8.71997 9.50998C8.71997 7.69998 10.18 6.22998 12 6.22998C13.81 6.22998 15.28 7.69998 15.28 9.50998C15.27 11.28 13.88 12.72 12.12 12.78Z" />
                                <path d="M18.74 19.3801C16.96 21.0101 14.6 22.0001 12 22.0001C9.40001 22.0001 7.04001 21.0101 5.26001 19.3801C5.36001 18.4401 5.96001 17.5201 7.03001 16.8001C9.77001 14.9801 14.25 14.9801 16.97 16.8001C18.04 17.5201 18.64 18.4401 18.74 19.3801Z" />
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" />
                            </svg>
                            <span>Ubah Profil</span>
                        </a>
                    </li>

                    <hr>
                    <li class="flex">
                        <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800" href="<?= site_url('logout') ?>">
                            <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Log out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>