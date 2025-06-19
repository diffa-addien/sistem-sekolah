<header class="z-30 md:z-10 py-4 bg-white shadow-md">
    <div class="container flex items-center justify-end h-full px-6 mx-auto text-blue-600">
        <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple" @click="isSideMenuOpen = !isSideMenuOpen" aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>

        <ul class="flex items-center flex-shrink-0 space-x-6">
            <li class="flex">
                <button class="rounded-md focus:outline-none focus:shadow-outline-purple" aria-label="Toggle color mode">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>
            </li>
            <li class="relative">
                <button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple" aria-label="Notifications" aria-haspopup="true">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    <span aria-hidden="true" class="absolute top-0 right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 bg-red-600 border-2 border-white rounded-full"></span>
                </button>
            </li>
            <li class="relative">
                <button class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none" aria-label="Account" aria-haspopup="true">
                    <img class="object-cover w-8 h-8 rounded-full" src="https://picsum.photos/id/237/200/200" alt="" aria-hidden="true">
                </button>
            </li>
        </ul>
    </div>
</header>