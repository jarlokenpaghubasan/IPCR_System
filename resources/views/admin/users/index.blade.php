<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Dashboard</title>
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

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-scale-in {
            animation: scaleIn 0.2s ease-out;
        }

        /* Compact user card for mobile */
        .user-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 0.75rem;
        }

        .user-card-photo {
            flex-shrink: 0;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            object-fit: cover;
            border: 2px solid #d1d5db;
        }

        .user-card-info {
            flex: 1;
            min-width: 0;
        }

        .user-card-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-card-role {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .user-card-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .user-card-action-btn {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .user-card-action-btn:hover {
            background: #f3f4f6;
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
            <!-- Top Header -->
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center gap-4">
                    <!-- Hamburger Menu Button (mobile only) -->
                    <button id="hamburgerBtn" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-gray-700 text-xl"></i>
                    </button>

                    <div class="flex-1">
                        <h2 class="text-lg sm:text-2xl font-bold text-gray-900">User Management</h2>
                        <p class="text-gray-600 text-xs sm:text-sm">Manage all users in the system</p>
                    </div>
                    <div class="flex items-center gap-3 text-right whitespace-nowrap hidden sm:flex">
                        <div class="text-right">
                            <p class="text-gray-900 font-semibold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-gray-600 text-xs">Admin</p>
                        </div>
                        @if(auth()->user()->hasProfilePhoto())
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center border-2 border-blue-600">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto p-4 sm:p-6 lg:p-8">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Filter and Search Section -->
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Search Bar -->
                    <div class="sm:col-span-2">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="text" 
                                id="searchInput" 
                                placeholder="Search by name, email, or username..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            >
                        </div>
                    </div>

                    <!-- Department Filter -->
                    <div class="relative">
                        <select 
                            id="departmentFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm appearance-none bg-white"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%228%22 viewBox=%220 0 12 8%22><path fill=%22%236b7280%22 d=%22M1 1l5 5 5-5%22/></svg>'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.2em 1.2em; padding-right: 2.5rem;"
                        >
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Create New User Button -->
                <div class="mb-6">
                    <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-plus"></i> Create New User
                    </a>
                </div>

                <!-- Desktop Table View (hidden on mobile) -->
                <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Photo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider hidden lg:table-cell">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Roles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider hidden lg:table-cell">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="usersTableBody">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-username="{{ strtolower($user->username) }}" data-department="{{ $user->department_id ?? '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->hasProfilePhoto())
                                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                                        @else
                                            <img src="/images/default_avatar.jpg" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden lg:table-cell">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @foreach($user->roles() as $role)
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold mr-1
                                                @if($role === 'admin') bg-purple-100 text-purple-800
                                                @elseif($role === 'director') bg-green-100 text-green-800
                                                @elseif($role === 'dean') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden lg:table-cell">
                                        {{ $user->department?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($user->is_active)
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2 flex items-center">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(auth()->user()->id !== $user->id)
                                            <form method="POST" action="{{ route('admin.users.toggleActive', $user) }}" class="inline" style="margin: 0;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-orange-600 hover:text-orange-900" title="Toggle Active">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline deleteForm" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="openConfirmationModal('{{ $user->name }}', this.form)" class="text-red-600 hover:text-red-900" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 text-sm">
                                        No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:text-blue-900">Create one now</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (shown on mobile, hidden on desktop) -->
                <div class="md:hidden" id="usersCardView">
                    @forelse($users as $user)
                        <div class="user-card user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-username="{{ strtolower($user->username) }}" data-department="{{ $user->department_id ?? '' }}">
                            <img 
                                @if($user->hasProfilePhoto())
                                    src="{{ $user->profile_photo_url }}"
                                @else
                                    src="/images/default_avatar.jpg"
                                @endif
                                alt="{{ $user->name }}" 
                                class="user-card-photo"
                            >
                            <div class="user-card-info">
                                <div class="user-card-name">{{ $user->name }}</div>
                                <div class="user-card-role">
                                    @foreach($user->roles() as $role)
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold mr-1
                                            @if($role === 'admin') bg-purple-100 text-purple-800
                                            @elseif($role === 'director') bg-green-100 text-green-800
                                            @elseif($role === 'dean') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ ucfirst($role) }}
                                        </span>
                                    @endforeach
                                    @if($user->is_active)
                                        <span class="ml-2 text-green-600"><i class="fas fa-check-circle"></i></span>
                                    @else
                                        <span class="ml-2 text-red-600"><i class="fas fa-times-circle"></i></span>
                                    @endif
                                </div>
                            </div>
                            <div class="user-card-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="user-card-action-btn text-blue-600 hover:bg-blue-50" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="user-card-action-btn text-green-600 hover:bg-green-50" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->user()->id !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline deleteForm" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openConfirmationModal('{{ $user->name }}', this.form)" class="user-card-action-btn text-red-600 hover:bg-red-50" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:text-blue-900">Create one now</a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6 text-sm hidden md:block">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            <div class="bg-red-50 border-b border-red-200 px-6 py-4 flex items-center gap-3">
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Confirm Delete</h2>
                    <p class="text-sm text-gray-600">This action cannot be undone</p>
                </div>
            </div>

            <div class="px-6 py-4">
                <p class="text-gray-700 mb-2 text-sm">Are you sure you want to delete <span id="deleteUserName" class="font-semibold text-gray-900">this user</span>?</p>
                <p class="text-sm text-gray-600">All associated data will be permanently removed from the system.</p>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeConfirmationModal()" class="px-4 py-2 rounded-lg font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition text-sm">
                    Cancel
                </button>
                <button type="button" onclick="confirmDelete()" class="px-4 py-2 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        let pendingDeleteForm = null;

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
                sidebar.classList.remove('sidebar-hidden');
                overlay.classList.add('hidden');
            } else {
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

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const departmentFilter = document.getElementById('departmentFilter');
        const userRows = document.querySelectorAll('.user-row');

        function filterUsers() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedDept = departmentFilter.value;

            userRows.forEach(row => {
                const name = row.dataset.name;
                const email = row.dataset.email;
                const username = row.dataset.username;
                const department = row.dataset.department;

                const matchesSearch = name.includes(searchTerm) || 
                                    email.includes(searchTerm) || 
                                    username.includes(searchTerm);
                const matchesDept = !selectedDept || department === selectedDept;

                if (matchesSearch && matchesDept) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterUsers);
        departmentFilter.addEventListener('change', filterUsers);

        // Confirmation modal functions
        function openConfirmationModal(userName, form) {
            document.getElementById('deleteUserName').textContent = userName;
            pendingDeleteForm = form;
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
            pendingDeleteForm = null;
        }

        function confirmDelete() {
            if (pendingDeleteForm) {
                pendingDeleteForm.submit();
            }
            closeConfirmationModal();
        }

        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmationModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmationModal();
            }
        });
    </script>
</body>
</html>