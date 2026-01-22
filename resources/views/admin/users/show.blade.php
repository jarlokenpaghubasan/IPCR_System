<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #sidebar {
            transition: transform 0.3s ease-in-out;
        }

        #sidebar.open {
            transform: translateX(0);
        }

        #sidebarOverlay {
            transition: opacity 0.3s ease-in-out;
        }

        #sidebarOverlay.visible {
            opacity: 1;
            pointer-events: auto;
        }

        #sidebarOverlay:not(.visible) {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen bg-gray-50">
        <!-- Desktop Sidebar (hidden on mobile) -->
        <div class="w-64 bg-white shadow-lg hidden lg:block">
            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                <p class="text-sm text-gray-600">IPCR/OPCR Module</p>
            </div>

            <nav class="p-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-semibold">
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

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 opacity-0 pointer-events-none lg:hidden"></div>

        <!-- Mobile Sidebar -->
        <div id="sidebar" class="fixed lg:hidden inset-y-0 left-0 w-64 bg-white shadow-lg z-40 -translate-x-full">
            <div class="p-6 border-b flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                    <p class="text-sm text-gray-600">IPCR/OPCR Module</p>
                </div>
                <button onclick="toggleSidebar()" class="p-2 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-times text-gray-700 text-xl"></i>
                </button>
            </div>

            <nav class="p-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-semibold">
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
            <!-- Header -->
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex justify-between items-center gap-3 sm:gap-4">
                    <!-- Hamburger (mobile/tablet only) -->
                    <button id="hamburgerBtn" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 flex-shrink-0">
                        <i class="fas fa-bars text-gray-700 text-xl"></i>
                    </button>

                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Admin Dashboard</h2>
                        <p class="text-gray-600 text-xs sm:text-sm">View User</p>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3 text-right whitespace-nowrap hidden sm:flex flex-shrink-0">
                        <div class="text-right hidden md:block">
                            <p class="text-gray-900 font-semibold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-gray-600 text-xs">Admin</p>
                        </div>
                        @if(auth()->user()->hasProfilePhoto())
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-blue-500 flex-shrink-0">
                        @else
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-blue-500 flex items-center justify-center border-2 border-blue-600 flex-shrink-0">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 sm:p-6 lg:p-8">
                    <!-- Back Button -->
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 mb-4 inline-flex items-center gap-2 text-sm font-medium">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>

                    <!-- User Card -->
                    <div class="bg-white rounded-lg shadow">
                        <!-- User Header (Photo + Name) -->
                        <div class="p-4 sm:p-6 lg:p-8 border-b">
                            <div class="flex flex-col md:flex-row md:items-start md:gap-8">
                                <!-- Photo -->
                                <div class="flex flex-col items-center md:items-start mb-4 md:mb-0">
                                    @if($user->hasProfilePhoto())
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-40 h-40 sm:w-48 sm:h-48 object-cover rounded-lg border-4 border-blue-500">
                                    @else
                                        <div class="w-40 h-40 sm:w-48 sm:h-48 bg-gray-200 rounded-lg flex items-center justify-center border-4 border-dashed border-gray-400">
                                            <i class="fas fa-user text-gray-400 text-6xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Name and Roles -->
                                <div class="flex-1 text-center md:text-left">
                                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                                    <div class="flex gap-2 mt-3 flex-wrap justify-center md:justify-start">
                                        @foreach($user->roles() as $role)
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                @if($role === 'admin') bg-purple-100 text-purple-800
                                                @elseif($role === 'director') bg-green-100 text-green-800
                                                @elseif($role === 'dean') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.users.edit', $user) }}" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2 text-sm">
                                        <i class="fas fa-edit"></i> Edit User
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="p-4 sm:p-6 lg:p-8 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Full Name</label>
                                    <p class="text-gray-900 text-sm sm:text-base">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Email</label>
                                    <p class="text-gray-900 text-sm sm:text-base break-all">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Username</label>
                                    <p class="text-gray-900 text-sm sm:text-base">{{ $user->username }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Phone</label>
                                    <p class="text-gray-900 text-sm sm:text-base">{{ $user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="p-4 sm:p-6 lg:p-8 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-2">Status</label>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <!-- Department & Designation -->
                        <div class="p-4 sm:p-6 lg:p-8 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Department & Designation</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Department</label>
                                    <p class="text-gray-900 text-sm sm:text-base">{{ $user->department->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-600 mb-1">Designation</label>
                                    <p class="text-gray-900 text-sm sm:text-base">{{ $user->designation->title ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-4 sm:p-6 lg:p-8 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-center text-sm">
                                Edit User
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold text-center text-sm">
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('visible');
        }

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

        // Close sidebar on navigation
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    toggleSidebar();
                }
            });
        });

        // Close sidebar when resizing to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('visible');
            }
        });
    </script>
</body>
</html>