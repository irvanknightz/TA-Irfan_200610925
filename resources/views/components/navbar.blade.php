<nav class="bg-gray-800" x-data="{ isOpen: false }">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
              <div class="flex-shrink-0">
                  <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
              </div>
              <div class="hidden md:block">
                  <div class="ml-10 flex items-baseline space-x-4">
                      <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                      <x-nav-link href="/home2" :active="request()->is('/home2')">AHP</x-nav-link>
                      <x-nav-link href="/about" :active="request()->is('Database')">Input Data</x-nav-link>
                      <x-nav-link href="/data" :active="request()->is('Data')">Data</x-nav-link>
                  </div>
              </div>
          </div>
          <div class="hidden md:flex items-center space-x-4">
              <x-nav-link href="/login" :active="request()->is('Login')">Logout</x-nav-link>
          </div>
          <div class="-mr-2 flex md:hidden">
              <!-- Mobile menu button -->
              <button type="button" @click="isOpen = !isOpen"
              class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
                  <span class="absolute -inset-0.5"></span>
                  <span class="sr-only">Open main menu</span>
                  <!-- Menu open: "hidden", Menu closed: "block" -->
                  <svg :class="{'hidden': isOpen,'block': !isOpen }" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                  </svg>
                  <!-- Menu open: "block", Menu closed: "hidden" -->
                  <svg  :class="{'block': isOpen, 'hidden': !isOpen }" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
              </button>
          </div>
      </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div x-show="isOpen" class="md:hidden" id="mobile-menu">
      <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
          <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
          <x-nav-link href="/home2" :active="request()->is('/home2')">AHP</x-nav-link>
          <x-nav-link href="/about" :active="request()->is('Database')">Input Data</x-nav-link>
          <x-nav-link href="/data" :active="request()->is('Data')">Data</x-nav-link>
          <x-nav-link href="/login" :active="request()->is('Login')">Logout</x-nav-link>
      </div>
      <!-- Profile section removed here -->
  </div>
</nav>

{{-- <nav class="bg-gray-800" x-data="{ isOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <x-nav-link href="/" :active="request()->is('/')">AHP</x-nav-link>
                        <x-nav-link href="/about" :active="request()->is('about')">Input Data</x-nav-link>
                        <x-nav-link href="/data" :active="request()->is('data')">Data</x-nav-link>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-4">
                @guest
                  <x-nav-link href="/login" :active="request()->is('login')">Login</x-nav-link>
                @else
                  <x-nav-link href="#" @click.prevent="$refs.logoutForm.submit()">Logout</x-nav-link>
                  <form ref="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
                      @csrf
                  </form>
                @endguest
            </div>
            <div class="-mr-2 flex md:hidden">
                <button type="button" @click="isOpen = !isOpen"
                class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <svg :class="{'hidden': isOpen,'block': !isOpen }" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg  :class="{'block': isOpen, 'hidden': !isOpen }" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="isOpen" class="md:hidden">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <x-nav-link href="/" :active="request()->is('/')">AHP</x-nav-link>
            <x-nav-link href="/about" :active="request()->is('about')">Input Data</x-nav-link>
            <x-nav-link href="/data" :active="request()->is('data')">Data</x-nav-link>
            @guest
              <x-nav-link href="/login" :active="request()->is('login')">Login</x-nav-link>
            @else
              <x-nav-link href="#" @click.prevent="$refs.logoutForm.submit()">Logout</x-nav-link>
              <form ref="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
                  @csrf
              </form>
            @endguest
        </div>
    </div>
</nav> --}}
