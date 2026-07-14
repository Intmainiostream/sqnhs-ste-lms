@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')

<script>
    window.usersData = @json($users->values());
</script>

<div x-data="{
    users: window.usersData.filter(u => u.status !== 'pending'),
    searchQuery: '',
    selectedRoles: [],
    currentPage: 1,
    perPage: 10,
    showFilters: false,
    showDetails: false,
    isEditMode: false,
    isSaving: false,
    isDeleting: false,
    selectedUser: null,
    _original: null,
    toast: { show: false, message: '', type: 'success' },
    confirmModal: { show: false, message: '', onConfirm: null },

    get filteredUsers() {
        let result = [...this.users];
        if (this.searchQuery.trim()) {
            const q = this.searchQuery.toLowerCase().trim();
            result = result.filter(u =>
                u.username.toLowerCase().includes(q) ||
                u.email.toLowerCase().includes(q) ||
                u.role.toLowerCase().includes(q) ||
                (u.first_name && u.first_name.toLowerCase().includes(q)) ||
                (u.last_name && u.last_name.toLowerCase().includes(q))
            );
        }
        if (this.selectedRoles.length > 0) {
            result = result.filter(u => this.selectedRoles.includes(u.role));
        }
        return result;
    },
    get totalPages() { return Math.max(1, Math.ceil(this.filteredUsers.length / this.perPage)); },
    get pagedUsers() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.filteredUsers.slice(start, start + this.perPage);
    },
    init() {
        this.$watch('searchQuery', () => this.currentPage = 1);
        this.$watch('selectedRoles', () => this.currentPage = 1);
    },

    displayName(u) {
        return (u.first_name || u.last_name) ? `${u.first_name ?? ''} ${u.last_name ?? ''}`.trim() : u.username;
    },

    roleBadge(role) {
        return {
            admin:   'bg-emerald-100 text-emerald-700',
            teacher: 'bg-sky-100 text-sky-700',
            parent:  'bg-violet-100 text-violet-700',
            student: 'bg-amber-100 text-amber-700',
        }[role] || 'bg-gray-100 text-gray-600';
    },

    statusBadge(status) {
        return {
            active:   'bg-green-50 text-green-700',
            pending:  'bg-amber-50 text-amber-700',
            inactive: 'bg-gray-100 text-gray-500',
        }[status] || 'bg-gray-100 text-gray-500';
    },

    viewUser(user) {
        this.selectedUser = { ...user };
        this.isEditMode = false;
        this.showDetails = true;
        document.body.style.overflow = 'hidden';
    },

    closeDetails() {
        this.showDetails = false;
        this.isEditMode = false;
        document.body.style.overflow = 'auto';
    },

    enableEdit() { this._original = { ...this.selectedUser }; this.isEditMode = true; },
    cancelEdit() { Object.assign(this.selectedUser, this._original); this.isEditMode = false; },

    showToast(message, type = 'success') {
        this.toast = { show: true, message, type };
        setTimeout(() => this.toast.show = false, 2500);
    },

    async saveUser() {
        this.isSaving = true;
        try {
            const res = await fetch(`/admin/users/${this.selectedUser.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    role: this.selectedUser.role,
                    status: this.selectedUser.status,
                    first_name: this.selectedUser.first_name,
                    last_name: this.selectedUser.last_name,
                    birthdate: this.selectedUser.birthdate,
                    grade_level: this.selectedUser.grade_level,
                    father_name: this.selectedUser.father_name,
                    father_contact: this.selectedUser.father_contact,
                    mother_name: this.selectedUser.mother_name,
                    mother_contact: this.selectedUser.mother_contact,
                    guardian_name: this.selectedUser.guardian_name,
                    guardian_contact: this.selectedUser.guardian_contact,
                }),
            });
            const data = await res.json();
            if (res.ok && data.success) {
                const idx = this.users.findIndex(u => u.id === this.selectedUser.id);
                if (idx !== -1) Object.assign(this.users[idx], {
                    role: this.selectedUser.role, status: this.selectedUser.status,
                    first_name: this.selectedUser.first_name, last_name: this.selectedUser.last_name,
                    birthdate: this.selectedUser.birthdate,
                    grade_level: this.selectedUser.grade_level,
                    father_name: this.selectedUser.father_name, father_contact: this.selectedUser.father_contact,
                    mother_name: this.selectedUser.mother_name, mother_contact: this.selectedUser.mother_contact,
                    guardian_name: this.selectedUser.guardian_name, guardian_contact: this.selectedUser.guardian_contact,
                });
                this.isEditMode = false;
                this.showToast('User updated successfully!', 'success');
            } else {
                this.showToast(data.message || 'Something went wrong.', 'error');
            }
        } catch (e) {
            this.showToast('Network error. Please try again.', 'error');
        }
        this.isSaving = false;
    },

    confirmDelete(user) {
        const newStatus = user.status === 'inactive' ? 'active' : 'inactive';
        const verb = newStatus === 'inactive' ? 'Deactivate' : 'Activate';

        this.confirmModal = {
            show: true,
            message: newStatus === 'inactive'
                ? `Deactivate ${user.username}? They won't be able to log in until reactivated.`
                : `Reactivate ${user.username}? They will be able to log in again.`,
            onConfirm: async () => {
                this.isDeleting = true;
                try {
                    const res = await fetch(`/admin/users/${user.id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            role: user.role,
                            status: newStatus,
                            first_name: user.first_name,
                            last_name: user.last_name,
                            birthdate: user.birthdate,
                            grade_level: user.grade_level,
                            father_name: user.father_name,
                            father_contact: user.father_contact,
                            mother_name: user.mother_name,
                            mother_contact: user.mother_contact,
                            guardian_name: user.guardian_name,
                            guardian_contact: user.guardian_contact,
                        }),
                    });
                    const data = await res.json();
                    if (res.ok && data.success) {
                        const idx = this.users.findIndex(u => u.id === user.id);
                        if (idx !== -1) this.users[idx].status = newStatus;
                        if (this.selectedUser && this.selectedUser.id === user.id) {
                            this.selectedUser.status = newStatus;
                        }
                        this.showDetails = false;
                        document.body.style.overflow = 'auto';
                        this.showToast(`User ${newStatus === 'inactive' ? 'deactivated' : 'activated'}.`, 'success');
                    } else {
                        this.showToast(data.message || `${verb} failed.`, 'error');
                    }
                } catch (e) {
                    this.showToast('Network error.', 'error');
                }
                this.isDeleting = false;
            }
        };
    },
}" @keydown.escape.window="closeDetails()" class="min-h-screen bg-gray-50">

    <div class="max-w-6xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

        {{-- HEADER BAR (now a contained rounded card, like the HRIS header) --}}
        <div class="anim-fade bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
            <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="3" fill="currentColor"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
                <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
            </svg>
            <div class="relative">
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Manage Users</h1>
                <p class="text-green-100 text-sm mt-1">All registered accounts across SQNHS STE ENROLLMENT SYSTEM</p>
            </div>

            <div class="relative grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                <template x-for="role in ['admin','teacher','parent','student']" :key="role">
                    <button @click="selectedRoles = selectedRoles.includes(role) ? selectedRoles.filter(r => r !== role) : [role]"
                        class="bg-white/10 backdrop-blur rounded-xl p-4 text-left hover:bg-white/20 transition-all duration-200 border"
                        :class="selectedRoles.includes(role) ? 'border-white/60' : 'border-white/0'">
                        <p class="text-2xl font-bold text-white" x-text="users.filter(u => u.role === role).length"></p>
                        <p class="text-green-100 text-xs uppercase tracking-wide mt-1 capitalize" x-text="role + 's'"></p>
                    </button>
                </template>
            </div>
        </div>

        {{-- CONTROLS + TABLE, now wrapped in one contained white card like the HRIS employee table --}}
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden mt-6">

            {{-- CONTROLS --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-4 sm:px-8 py-4 border-b border-gray-100">
                <div class="relative flex-1 sm:max-w-xs group">
                    <input type="text" x-model="searchQuery" placeholder="Search users..."
                        autocomplete="off"
                        class="w-full pl-9 pr-8 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button x-show="searchQuery.length > 0" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="relative">
                    <button @click="showFilters = !showFilters"
                        class="py-2 px-3.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition flex items-center gap-1.5 text-sm text-gray-600 relative"
                        :class="{ 'bg-green-50 border-green-300 text-green-700': showFilters || selectedRoles.length > 0 }">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                        <span x-show="selectedRoles.length > 0"
                            class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-green-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white"
                            x-text="selectedRoles.length"></span>
                    </button>

                    <div x-show="showFilters" @click.away="showFilters = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute left-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-100 z-30 overflow-hidden"
                        style="display: none;">
                        <div class="p-2.5 space-y-0.5">
                            <template x-for="role in ['admin','teacher','parent','student']" :key="role">
                                <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" :value="role" x-model="selectedRoles" class="w-3.5 h-3.5 rounded border-gray-300 text-green-600 focus:ring-0">
                                    <span class="text-sm text-gray-700 capitalize" x-text="role"></span>
                                </label>
                            </template>
                        </div>
                        <div class="px-2.5 py-1.5 border-t border-gray-100">
                            <button @click="selectedRoles = []" class="text-xs text-gray-400 hover:text-gray-600">Clear</button>
                        </div>
                    </div>
                </div>

                <div x-show="selectedRoles.length > 0" class="flex flex-wrap items-center gap-1.5">
                    <template x-for="role in selectedRoles" :key="role">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100 capitalize">
                            <span x-text="role"></span>
                            <button @click="selectedRoles = selectedRoles.filter(r => r !== role)" class="ml-1 hover:text-green-900">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    </template>
                </div>
            </div>

            {{-- TABLE --}}
            <table class="w-full text-sm hidden sm:table">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-left text-xs uppercase tracking-wide border-b border-gray-100">
                        <th class="px-4 sm:px-8 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Registered</th>
                        <th class="px-4 sm:px-8 py-3 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="user in pagedUsers" :key="user.id">
                        <tr class="hover:bg-green-50/40 transition-colors duration-150">
                            <td class="px-4 sm:px-8 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-700 text-white flex items-center justify-center text-xs font-bold flex-shrink-0"
                                        x-text="displayName(user).substring(0,2).toUpperCase()"></div>
                                    <div>
                                        <p class="font-medium text-gray-900" x-text="displayName(user)"></p>
                                        <p class="text-xs text-gray-400" x-text="user.username"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600" x-text="user.email"></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium capitalize" :class="roleBadge(user.role)" x-text="user.role"></span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium capitalize" :class="statusBadge(user.status)" x-text="user.status"></span>
                            </td>
                            <td class="px-4 py-3 text-gray-500" x-text="user.created_at"></td>
                            <td class="px-4 sm:px-8 py-3 text-right">
                                <button @click="viewUser(user)"
                                    class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">
                                    View
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredUsers.length === 0">
                        <td colspan="6" class="px-8 py-16 text-center text-gray-400 text-sm">No users match your search.</td>
                    </tr>
                </tbody>
            </table>

            {{-- Mobile cards --}}
            <div class="sm:hidden divide-y divide-gray-100">
                <template x-for="user in pagedUsers" :key="user.id">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-green-700 text-white flex items-center justify-center text-xs font-bold"
                                    x-text="displayName(user).substring(0,2).toUpperCase()"></div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm" x-text="displayName(user)"></p>
                                    <p class="text-xs text-gray-500" x-text="user.email"></p>
                                </div>
                            </div>
                            <button @click="viewUser(user)" class="px-3 py-1.5 rounded-lg bg-green-600 text-white text-xs font-medium">View</button>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium capitalize" :class="roleBadge(user.role)" x-text="user.role"></span>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium capitalize" :class="statusBadge(user.status)" x-text="user.status"></span>
                        </div>
                    </div>
                </template>
                <div x-show="filteredUsers.length === 0" class="p-10 text-center text-gray-400 text-sm">No users match your search.</div>
            </div>

            <div x-show="filteredUsers.length > 0" class="px-4 sm:px-8 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-gray-500">
                    Showing
                    <span class="font-medium" x-text="Math.min((currentPage - 1) * perPage + 1, filteredUsers.length)"></span>–<span class="font-medium" x-text="Math.min(currentPage * perPage, filteredUsers.length)"></span>
                    of <span class="font-medium" x-text="filteredUsers.length"></span> users
                </p>
                <div x-show="totalPages > 1" class="flex items-center gap-2">
                    <button @click="currentPage > 1 && currentPage--" :disabled="currentPage === 1"
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition-all duration-200 disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <template x-for="page in Array.from({length: totalPages}, (_, i) => i + 1)" :key="page">
                        <button @click="currentPage = page"
                            :class="currentPage === page ? 'bg-green-600 text-white hover:bg-green-700' : 'border border-gray-200 bg-white text-gray-600 hover:bg-green-50 hover:text-green-700 hover:border-green-200'"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition-all duration-200"
                            x-text="page"></button>
                    </template>
                    <button @click="currentPage < totalPages && currentPage++" :disabled="currentPage === totalPages"
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition-all duration-200 disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- USER DETAILS / EDIT MODAL --}}
    <div x-show="showDetails" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.5); backdrop-filter: blur(6px);"
        @click.self="closeDetails()">
        <div x-show="showDetails"
            x-transition:enter="transition-all duration-250 ease-out"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition-all duration-150 ease-in"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden relative"
            @click.stop>

            <button @click="closeDetails()"
                class="absolute top-5 right-5 w-9 h-9 flex items-center justify-center rounded-full border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all z-10 bg-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <template x-if="selectedUser">
                <div>
                    <div class="px-8 pt-8 pb-2">
                        <div class="w-14 h-14 rounded-2xl bg-green-700 text-white flex items-center justify-center text-xl font-bold mb-4"
                            x-text="displayName(selectedUser).substring(0,2).toUpperCase()"></div>
                        <h2 class="text-xl font-bold text-gray-900" x-text="displayName(selectedUser)"></h2>
                        <p class="text-sm text-gray-500 mt-0.5" x-text="selectedUser.email"></p>
                    </div>

                    <div class="px-8 py-5 space-y-4 max-h-[65vh] overflow-y-auto">
                        <div class="grid grid-cols-2 gap-3" x-show="selectedUser.first_name !== null || isEditMode">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">First Name</label>
                                <input type="text" x-model="selectedUser.first_name" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Last Name</label>
                                <input type="text" x-model="selectedUser.last_name" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                        </div>

                        <div x-show="selectedUser.first_name !== null || isEditMode">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Birthdate</label>
                            <input type="date" x-model="selectedUser.birthdate" :readonly="!isEditMode"
                                :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                        </div>

                        <div x-show="selectedUser.role === 'student' && (selectedUser.grade_level !== null || isEditMode)">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Grade Level</label>
                            <div class="relative">
                                <select x-model.number="selectedUser.grade_level" :disabled="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none transition-all">
                                    <option value="7">Grade 7</option>
                                    <option value="8">Grade 8</option>
                                    <option value="9">Grade 9</option>
                                    <option value="10">Grade 10</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div x-show="selectedUser.current_address">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Current Address</label>
                            <input type="text" x-model="selectedUser.current_address" readonly
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed">
                            <p class="text-xs text-gray-400 mt-1">Address changes are submitted by the student for approval.</p>
                        </div>

                        <div x-show="selectedUser.permanent_address">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Permanent Address</label>
                            <input type="text" x-model="selectedUser.permanent_address" readonly
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed">
                        </div>

                        <div class="grid grid-cols-2 gap-3" x-show="selectedUser.first_name !== null || isEditMode">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Father's Name</label>
                                <input type="text" x-model="selectedUser.father_name" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Father's Contact</label>
                                <input type="text" x-model="selectedUser.father_contact" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3" x-show="selectedUser.first_name !== null || isEditMode">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Mother's Name</label>
                                <input type="text" x-model="selectedUser.mother_name" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Mother's Contact</label>
                                <input type="text" x-model="selectedUser.mother_contact" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3" x-show="selectedUser.first_name !== null || isEditMode">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Guardian's Name</label>
                                <input type="text" x-model="selectedUser.guardian_name" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Guardian's Contact</label>
                                <input type="text" x-model="selectedUser.guardian_contact" :readonly="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Role</label>
                            <div class="relative">
                                <select x-model="selectedUser.role" :disabled="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none capitalize transition-all">
                                    <option value="admin">Admin</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="parent">Parent</option>
                                    <option value="student">Student</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Status</label>
                            <div class="relative">
                                <select x-model="selectedUser.status" :disabled="!isEditMode"
                                    :class="{'bg-gray-50 cursor-not-allowed': !isEditMode, 'bg-white': isEditMode}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none capitalize transition-all">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Date Registered</label>
                            <p class="text-sm text-gray-700 px-1" x-text="selectedUser.created_at"></p>
                        </div>
                    </div>

                    <div class="px-8 py-5 flex items-center justify-between border-t border-gray-100">
                        <button @click="confirmDelete(selectedUser)" :disabled="isDeleting"
                            :class="selectedUser.status === 'inactive' ? 'text-green-600 hover:text-green-700' : 'text-red-500 hover:text-red-700'"
                            class="text-sm font-medium transition-colors">
                            <span x-text="selectedUser.status === 'inactive' ? 'Activate user' : 'Deactivate user'"></span>
                        </button>
                        <div class="flex gap-2">
                            <template x-if="!isEditMode">
                                <button @click="enableEdit()"
                                    class="px-6 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-xl border border-gray-300 hover:bg-gray-50 transition-all">
                                    Edit
                                </button>
                            </template>
                            <template x-if="isEditMode">
                                <button @click="cancelEdit()"
                                    class="px-6 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-xl border border-gray-300 hover:bg-gray-50 transition-all">
                                    Cancel
                                </button>
                            </template>
                            <template x-if="isEditMode">
                                <button @click="saveUser()" :disabled="isSaving"
                                    :class="isSaving ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                    class="px-6 py-2.5 text-white text-sm font-medium rounded-xl transition-all">
                                    <span x-show="!isSaving">Save</span>
                                    <span x-show="isSaving">Saving...</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- CONFIRM DELETE MODAL --}}
    <div x-show="confirmModal.show" x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.45); backdrop-filter: blur(6px);">
        <div x-show="confirmModal.show"
            x-transition:enter="transition-all duration-200 ease-out"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl shadow-2xl flex flex-col items-center text-center p-8"
            style="width: 380px;">
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-800 mb-1">Confirm Action</p>
            <p class="text-sm text-gray-500 mb-6 leading-snug" x-text="confirmModal.message"></p>
            <div class="flex gap-3">
                <button @click="confirmModal.show = false" class="px-6 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                <button @click="confirmModal.onConfirm(); confirmModal.show = false"
                    :class="selectedUser && selectedUser.status === 'inactive' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
                    class="px-6 py-2.5 text-sm font-semibold rounded-xl text-white transition-all">
                    <span x-text="selectedUser && selectedUser.status === 'inactive' ? 'Activate' : 'Deactivate'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- TOAST --}}
    <div x-show="toast.show" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center pointer-events-none" style="display: none;">
        <div x-show="toast.show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="bg-white rounded-2xl shadow-2xl flex flex-col items-center text-center px-10 py-8"
            style="width: 340px;">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                :class="toast.type === 'success' ? 'bg-green-50' : 'bg-red-50'">
                <template x-if="toast.type === 'success'">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </template>
                <template x-if="toast.type !== 'success'">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </template>
            </div>
            <p class="text-base font-semibold text-gray-800" x-text="toast.message"></p>
        </div>
    </div>

</div>

<style>
    .anim-fade { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    [x-cloak] { display: none !important; }
</style>
@endsection