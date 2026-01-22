<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IPCR/OPCR Module</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-hidden {
            display: none;
        }
        @media (min-width: 1024px) {
            .sidebar-hidden {
                display: block !important;
            }
        }
        
        /* Sidebar slide animation */
        #sidebar {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        
        @media (max-width: 1023px) {
            #sidebar.sidebar-hidden {
                transform: translateX(-100%);
                opacity: 0;
                pointer-events: none;
            }
            
            #sidebar:not(.sidebar-hidden) {
                transform: translateX(0);
                opacity: 1;
                pointer-events: auto;
            }
        }
        
        /* Overlay fade animation */
        #sidebarOverlay {
            transition: opacity 0.3s ease-in-out;
        }
        
        #sidebarOverlay.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        #sidebarOverlay:not(.hidden) {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-50">
        <!-- Mobile Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden" onclick="toggleSidebar()"></div>

        <!-- Sidebar Navigation -->
        <div id="sidebar" class="sidebar-hidden fixed lg:relative inset-y-0 left-0 w-64 bg-white shadow-lg z-40">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                <p class="text-sm text-gray-600">IPCR/OPCR Module</p>
            </div>

            <nav class="p-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-users w-5"></i>
                    <span>User Management</span>
                </a>

                <hr class="my-4">

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition w-full">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center gap-4">
                    <!-- Hamburger Menu Button (mobile only) -->
                    <button id="hamburgerBtn" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-gray-700 text-xl"></i>
                    </button>

                    <div class="flex-1">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Administrator Dashboard</h2>
                        <p class="text-gray-600 text-xs sm:text-sm">IPCR and OPCR Management System</p>
                    </div>
                    <div class="flex items-center gap-3 text-right">
                        <div class="text-right whitespace-nowrap">
                            <p class="text-gray-900 font-semibold text-sm sm:text-base">{{ auth()->user()->name }}</p>
                            <p class="text-gray-600 text-xs sm:text-sm">Admin</p>
                        </div>
                        @if(auth()->user()->hasProfilePhoto())
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-blue-500">
                        @else
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-500 flex items-center justify-center border-2 border-blue-600">
                                <i class="fas fa-user text-white text-sm sm:text-base"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-4 sm:p-6 lg:p-8">
                <!-- Welcome Section -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 sm:p-8 text-white mb-8">
                    <h3 class="text-2xl sm:text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-blue-100">Manage the IPCR/OPCR system from the admin panel</p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total Users</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::count() }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-4">
                                <i class="fas fa-users text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Active Users -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Active Users</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('is_active', true)->count() }}</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-4">
                                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Departments -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Departments</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Department::count() }}</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-4">
                                <i class="fas fa-building text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Sections -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Management Card -->
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <div class="bg-blue-500 h-40 sm:h-48 flex flex-col items-center justify-center flex-shrink-0">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-blue-400 flex items-center justify-center">
                                <i class="fas fa-users text-white text-4xl sm:text-5xl"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">User Management</h3>
                            <p class="text-gray-600 text-sm mb-4">Create, edit, view, and manage all user accounts in the system</p>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition text-sm">
                                <i class="fas fa-arrow-right"></i> Manage Users
                            </a>
                        </div>
                    </div>

                    <!-- Coming Soon Card -->
                    <div class="bg-white rounded-lg shadow overflow-hidden opacity-50">
                        <div class="bg-gray-400 h-40 sm:h-48 flex flex-col items-center justify-center flex-shrink-0">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-lock text-white text-4xl sm:text-5xl"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">More Features Coming</h3>
                            <p class="text-gray-600 text-sm mb-4">Additional admin features will be added here in future updates</p>
                            <button disabled class="inline-flex items-center gap-2 bg-gray-400 text-white px-4 py-2 rounded-lg font-semibold cursor-not-allowed text-sm">
                                <i class="fas fa-clock"></i> Coming Soon
                            </button>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="bg-white rounded-lg shadow p-6 mt-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">System Information</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <div>
                            <p class="text-gray-600 text-xs sm:text-sm">Laravel Version</p>
                            <p class="text-gray-900 font-semibold text-sm sm:text-base">12.47.0</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs sm:text-sm">PHP Version</p>
                            <p class="text-gray-900 font-semibold text-sm sm:text-base">8.2.12</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs sm:text-sm">Database</p>
                            <p class="text-gray-900 font-semibold text-sm sm:text-base">MySQL</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs sm:text-sm">Environment</p>
                            <p class="text-gray-900 font-semibold text-sm sm:text-base">Local</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('sidebar-hidden');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar when clicking a link
        document.querySelectorAll('#sidebar a, #sidebar button').forEach(element => {
            element.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    document.getElementById('sidebar').classList.add('sidebar-hidden');
                    document.getElementById('sidebarOverlay').classList.add('hidden');
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth >= 1024) {
                // Desktop size - show sidebar
                sidebar.classList.remove('sidebar-hidden');
                overlay.classList.add('hidden');
            } else {
                // Mobile size - hide sidebar
                sidebar.classList.add('sidebar-hidden');
                overlay.classList.add('hidden');
            }
        });

        // Initialize on page load
        window.addEventListener('load', () => {
            const sidebar = document.getElementById('sidebar');
            
            if (window.innerWidth < 1024) {
                sidebar.classList.add('sidebar-hidden');
            }
        });
    </script>
</body>
</html>