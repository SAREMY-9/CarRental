<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for reactivity -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Lucide icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans">
    <!-- Sidebar -->
    <div class="flex h-screen overflow-hidden">

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" 
             x-transition.opacity 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>

        <aside class="fixed inset-y-0 left-0 transform bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 w-64 z-40 
                       lg:translate-x-0 transition-transform duration-300 ease-in-out"
               :class="{ '-translate-x-full': !sidebarOpen }">

            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">🚘 Admin</h1>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-200">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('vehicles.index') }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-lg 
                          text-gray-700 dark:text-gray-300 
                          hover:bg-blue-500 hover:text-white transition
                          {{ request()->routeIs('vehicles.*') ? 'bg-blue-500 text-white' : '' }}">
                    <i data-lucide="car"></i> <span>Vehicles</span>
                </a>


                <a href="{{ route('bookings.index') }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-lg 
                          text-gray-700 dark:text-gray-300 
                          hover:bg-blue-500 hover:text-white transition">
                    <i data-lucide="clipboard-list"></i> <span>Bookings</span>
                </a>

                <a href="#" 
                   class="flex items-center gap-2 px-3 py-2 rounded-lg 
                          text-gray-700 dark:text-gray-300 
                          hover:bg-blue-500 hover:text-white transition">
                    <i data-lucide="users"></i> <span>Users</span>
                </a>
            </nav>

            <div class="absolute bottom-0 w-full p-4 border-t dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg 
                               text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900 transition">
                        <i data-lucide="log-out"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
            
            <!-- Topbar -->
            <header class="flex items-center justify-between bg-white dark:bg-gray-800 border-b dark:border-gray-700 px-4 py-3">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 dark:text-gray-300">
                        <i data-lucide="menu"></i>
                    </button>
                    <h2 class="text-lg font-semibold">@yield('title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-4">
                    <button @click="darkMode = !darkMode" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i :data-lucide="darkMode ? 'sun' : 'moon'"></i>
                    </button>
                    <span class="text-sm text-gray-500 dark:text-gray-300">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6 overflow-y-auto flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
