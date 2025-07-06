<header class="z-30 md:z-10 py-4 bg-white shadow-sm">
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