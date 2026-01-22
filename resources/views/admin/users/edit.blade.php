<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Dashboard</title>
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

                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg sm:text-2xl font-bold text-gray-900">Edit User</h2>
                        <p class="text-gray-600 text-xs sm:text-sm truncate">{{ $user->name }}</p>
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
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900 mb-6 inline-flex items-center gap-2 text-sm">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="text-red-800 font-semibold mb-2 text-sm">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-red-700 text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Photo Section (Left/Top on mobile, Left on desktop) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-4 lg:p-6 lg:sticky lg:top-0">
                            <h3 class="text-base lg:text-lg font-semibold text-gray-900 mb-3 lg:mb-4">Profile Photo</h3>

                            <!-- Current Profile Photo -->
                            <div class="mb-4 lg:mb-6">
                                <div class="w-full aspect-square bg-gray-200 rounded-lg mb-2 flex items-center justify-center border-2 border-dashed border-gray-400 overflow-hidden">
                                    @if($user->hasProfilePhoto())
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover border-2 border-blue-500">
                                    @else
                                        <i class="fas fa-user text-gray-400 text-3xl lg:text-5xl"></i>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 text-center">@if($user->hasProfilePhoto())Current Profile Photo @else No Profile Photo @endif</p>
                            </div>

                            <!-- Photo Upload Form -->
                            <div class="border-t pt-4 lg:pt-6">
                                <label class="block text-xs lg:text-sm font-medium text-gray-700 mb-2 lg:mb-3">Upload Photo</label>
                                <form id="photoUploadForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3 lg:mb-4 flex gap-2 sm:gap-3">
                                        <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden">
                                        <button type="button" onclick="document.getElementById('photoInput').click()" class="flex-1 px-3 lg:px-4 py-1.5 lg:py-2 border-2 border-dashed border-blue-300 rounded-lg text-center text-blue-600 hover:bg-blue-50 transition cursor-pointer text-xs lg:text-sm whitespace-nowrap">
                                            <i class="fas fa-cloud-upload-alt mr-1"></i>
                                            <span id="uploadText" class="hidden sm:inline">Choose Photo</span>
                                            <span id="uploadTextShort" class="sm:hidden">Upload</span>
                                        </button>
                                    </div>
                                    <div id="uploadProgress" class="hidden mb-2 lg:mb-3">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">Uploading...</p>
                                    </div>
                                    <div id="uploadMessage" class="text-xs mb-2 lg:mb-3"></div>
                                    <p class="text-xs text-gray-500">Max 5MB • JPEG, PNG, GIF, WebP</p>
                                </form>
                            </div>

                            <!-- All Photos -->
                            <div class="border-t pt-4 lg:pt-6 mt-4 lg:mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2 lg:mb-3 text-xs lg:text-sm">All Photos (<span id="photoCount">0</span>)</h4>
                                <div id="allPhotos" class="grid grid-cols-4 lg:grid-cols-3 gap-1.5 lg:gap-2 max-h-48 lg:max-h-64 overflow-y-auto">
                                    <!-- Photos loaded via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Form (Right on desktop, below photo on mobile) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow p-6">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Personal Information -->
                                <div class="border-b pb-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Full Name -->
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('name')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('email')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Username -->
                                        <div>
                                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('username')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Phone -->
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('phone')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Information -->
                                <div class="border-b pb-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Account Information</h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Password -->
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (Leave blank to keep current)</label>
                                            <input type="password" name="password" id="password" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                            @error('password')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                        </div>

                                        <!-- Roles (Multiple Selection) -->
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Roles *</label>
                                            <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                                                @php
                                                    $availableRoles = ['admin', 'director', 'dean', 'faculty'];
                                                    $userRoles = $user->roles();
                                                @endphp
                                                
                                                @foreach($availableRoles as $role)
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="checkbox" 
                                                            name="roles[]" 
                                                            id="role_{{ $role }}" 
                                                            value="{{ $role }}"
                                                            {{ in_array($role, $userRoles) ? 'checked' : '' }}
                                                            class="w-4 h-4 text-blue-600 rounded"
                                                        >
                                                        <label for="role_{{ $role }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                            {{ ucfirst($role) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">Select one or more roles</p>
                                            @error('roles')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Status -->
                                        <div>
                                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <div class="flex items-center gap-2 mt-2">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                                                <label for="is_active" class="text-gray-700 text-sm">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Department & Designation -->
                                <div class="border-b pb-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Department & Designation</h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Department -->
                                        <div>
                                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                            <select name="department_id" id="department_id" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                                <option value="">Select a department</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('department_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>

                                        <!-- Designation -->
                                        <div>
                                            <label for="designation_id" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                            <select name="designation_id" id="designation_id" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                                <option value="">Select a designation</option>
                                                @foreach($designations as $desig)
                                                    <option value="{{ $desig->id }}" {{ old('designation_id', $user->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('designation_id')<span class="text-red-600 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center justify-center gap-2 text-sm">
                                        <i class="fas fa-save"></i> Update User
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold text-center text-sm">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const photoInput = document.getElementById('photoInput');
        const uploadForm = document.getElementById('photoUploadForm');
        const uploadProgress = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const uploadMessage = document.getElementById('uploadMessage');
        const uploadText = document.getElementById('uploadText');
        const userId = {{ $user->id }};

        let pendingDeleteForm = null;

        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('sidebar-hidden');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar when clicking a link (mobile only, auto close)
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

        // Photo input change event
        photoInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                uploadText.textContent = this.files[0].name;
                uploadPhoto(this.files[0]);
            }
        });

        // Upload photo via AJAX
        function uploadPhoto(file) {
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            uploadProgress.classList.remove('hidden');
            uploadMessage.innerHTML = '';

            fetch(`/admin/panel/users/${userId}/photo/upload`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                uploadProgress.classList.add('hidden');
                if (data.success) {
                    uploadMessage.innerHTML = '<span class="text-green-600 text-xs"><i class="fas fa-check-circle mr-1"></i>' + data.message + '</span>';
                    photoInput.value = '';
                    uploadText.textContent = 'Choose Photo';
                    loadPhotos();
                } else {
                    uploadMessage.innerHTML = '<span class="text-red-600 text-xs"><i class="fas fa-times-circle mr-1"></i>' + data.message + '</span>';
                }
            })
            .catch(error => {
                uploadProgress.classList.add('hidden');
                uploadMessage.innerHTML = '<span class="text-red-600 text-xs"><i class="fas fa-times-circle mr-1"></i>Upload failed</span>';
                console.error('Error:', error);
            });
        }

        // Load all photos
        function loadPhotos() {
            fetch(`/admin/panel/users/${userId}/photos`)
                .then(response => response.json())
                .then(data => {
                    const allPhotos = document.getElementById('allPhotos');
                    const photoCount = document.getElementById('photoCount');
                    photoCount.textContent = data.photos.length;
                    
                    if (data.photos.length === 0) {
                        allPhotos.innerHTML = '<p class="text-gray-600 text-xs col-span-3">No photos yet</p>';
                        return;
                    }

                    allPhotos.innerHTML = data.photos.map(photo => `
                        <div class="relative group">
                            <img src="${photo.url}" alt="Photo" class="w-full aspect-square object-cover rounded-lg border-2 ${photo.is_profile ? 'border-blue-500' : 'border-gray-300'}">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 rounded-lg transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                ${!photo.is_profile ? `<a href="/admin/panel/users/${userId}/photos/${photo.id}/set-profile" class="bg-white text-blue-600 p-1.5 rounded text-xs hover:bg-blue-50" title="Set as profile"><i class="fas fa-star"></i></a>` : '<span class="bg-blue-500 text-white px-2 py-1 rounded text-xs font-semibold">Profile</span>'}
                                <form method="POST" action="/admin/panel/users/${userId}/photos/${photo.id}" class="inline" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white p-1.5 rounded text-xs hover:bg-red-700" title="Delete" onclick="return confirm('Delete this photo?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            ${photo.is_profile ? '<span class="absolute top-1 right-1 bg-blue-500 text-white px-2 py-1 rounded text-xs font-semibold"><i class="fas fa-check"></i></span>' : ''}
                        </div>
                    `).join('');
                });
        }

        // Load photos on page load
        loadPhotos();
    </script>
</body>
</html>