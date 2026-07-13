@extends('student.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

    <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
        <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
            <circle cx="32" cy="32" r="3" fill="currentColor"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
        </svg>
        <div class="relative">
            <h1 class="text-2xl sm:text-3xl font-bold text-white">My Profile</h1>
            <p class="text-green-100 text-sm mt-1">Manage your student information and password</p>
        </div>
    </div>

    

    {{-- Account (username/email, read-only) --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Account</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-400 text-xs">Username</p><p class="text-gray-800 font-medium">{{ $user->username }}</p></div>
            <div><p class="text-gray-400 text-xs">Email</p><p class="text-gray-800 font-medium">{{ $user->email }}</p></div>
        </div>
    </div>

    {{-- Pending request notice --}}
    @if($pendingRequest)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mt-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-amber-800">Pending Information Update</p>
                    <p class="text-sm text-amber-700 mt-1">Your submitted changes are waiting for admin approval.</p>
                </div>
                <form method="POST" action="{{ route('student.profile.cancel', $pendingRequest) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:underline">Cancel</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Read-only summary --}}
    <div id="infoSummary" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Student Information</h2>
            @unless($pendingRequest)
                <button type="button" onclick="toggleEdit(true)" class="text-sm text-green-700 font-medium hover:underline">Edit Information</button>
            @endunless
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-400 text-xs">LRN</p><p class="text-gray-800 font-medium">{{ $student->lrn ?: '—' }}</p></div>
            <div><p class="text-gray-400 text-xs">PSA Birth Certificate No.</p><p class="text-gray-800 font-medium">{{ $student->psa_birth_cert_no ?: '—' }}</p></div>
            <div><p class="text-gray-400 text-xs">Full Name</p><p class="text-gray-800 font-medium">{{ trim("{$student->first_name} {$student->middle_name} {$student->last_name}") }}</p></div>
            <div><p class="text-gray-400 text-xs">Birthdate</p><p class="text-gray-800 font-medium">{{ $student->birthdate?->format('F j, Y') }}</p></div>
            <div><p class="text-gray-400 text-xs">Sex</p><p class="text-gray-800 font-medium">{{ $student->sex }}</p></div>
            <div><p class="text-gray-400 text-xs">Place of Birth</p><p class="text-gray-800 font-medium">{{ $student->place_of_birth ?: '—' }}</p></div>
            <div><p class="text-gray-400 text-xs">Mother Tongue</p><p class="text-gray-800 font-medium">{{ $student->mother_tongue ?: '—' }}</p></div>
            <div><p class="text-gray-400 text-xs">IP Community</p><p class="text-gray-800 font-medium">{{ $student->is_ip ? ($student->ip_specify ?: 'Yes') : 'No' }}</p></div>
            <div><p class="text-gray-400 text-xs">4Ps Beneficiary</p><p class="text-gray-800 font-medium">{{ $student->is_4ps ? ($student->household_id ?: 'Yes') : 'No' }}</p></div>
            <div><p class="text-gray-400 text-xs">Disability</p><p class="text-gray-800 font-medium">{{ $student->has_disability ? ($student->disability_type ?: 'Yes') : 'No' }}</p></div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-gray-400 text-xs mb-1">Current Address</p>
            <p class="text-gray-800 text-sm font-medium">
                {{ collect([$student->current_house_no, $student->current_street, $student->current_barangay, $student->current_city, $student->current_province, $student->current_zip])->filter()->implode(', ') }}
            </p>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-gray-400 text-xs mb-1">Permanent Address</p>
            <p class="text-gray-800 text-sm font-medium">
                @if($student->same_as_current)
                    Same as current address
                @else
                    {{ collect([$student->permanent_house_no, $student->permanent_street, $student->permanent_barangay, $student->permanent_city, $student->permanent_province, $student->permanent_zip])->filter()->implode(', ') ?: '—' }}
                @endif
            </p>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div><p class="text-gray-400 text-xs">Father</p><p class="text-gray-800 font-medium">{{ $student->father_name ?: '—' }}</p><p class="text-gray-500 text-xs">{{ $student->father_contact }}</p></div>
            <div><p class="text-gray-400 text-xs">Mother</p><p class="text-gray-800 font-medium">{{ $student->mother_name ?: '—' }}</p><p class="text-gray-500 text-xs">{{ $student->mother_contact }}</p></div>
            <div><p class="text-gray-400 text-xs">Guardian</p><p class="text-gray-800 font-medium">{{ $student->guardian_name ?: '—' }}</p><p class="text-gray-500 text-xs">{{ $student->guardian_contact }}</p></div>
        </div>
    </div>

    {{-- Edit form (hidden by default) --}}
    <div id="infoEditForm" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4" style="display: none;">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Edit Student Information</h2>
            <button type="button" onclick="toggleEdit(false)" class="text-sm text-gray-500 hover:underline">Cancel</button>
        </div>

        <form method="POST" action="{{ route('student.profile.info') }}" class="space-y-6">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">LRN</label>
                    <input type="text" name="lrn" value="{{ old('lrn', $student->lrn) }}" maxlength="12"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">PSA Birth Certificate No.</label>
                    <input type="text" name="psa_birth_cert_no" value="{{ old('psa_birth_cert_no', $student->psa_birth_cert_no) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Birthdate</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $student->birthdate?->format('Y-m-d')) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Sex</label>
                    <select name="sex" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="Male" {{ old('sex', $student->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $student->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Place of Birth</label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $student->place_of_birth) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-green-800 mb-1">Mother Tongue</label>
                <input type="text" name="mother_tongue" value="{{ old('mother_tongue', $student->mother_tongue) }}"
                       class="w-full sm:w-1/3 rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Belonging to any IP Community?</label>
                    <div class="flex gap-4 items-center">
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_ip" value="1" {{ old('is_ip', $student->is_ip) ? 'checked' : '' }}> Yes
                        </label>
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_ip" value="0" {{ !old('is_ip', $student->is_ip) ? 'checked' : '' }}> No
                        </label>
                    </div>
                    <input type="text" name="ip_specify" value="{{ old('ip_specify', $student->ip_specify) }}" placeholder="If yes, specify"
                           class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">4Ps Beneficiary?</label>
                    <div class="flex gap-4 items-center">
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_4ps" value="1" {{ old('is_4ps', $student->is_4ps) ? 'checked' : '' }}> Yes
                        </label>
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_4ps" value="0" {{ !old('is_4ps', $student->is_4ps) ? 'checked' : '' }}> No
                        </label>
                    </div>
                    <input type="text" name="household_id" value="{{ old('household_id', $student->household_id) }}" placeholder="Household ID, if yes"
                           class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-green-800 mb-1">Learner with Disability?</label>
                <div class="flex gap-4 items-center mb-2">
                    <label class="flex items-center gap-1 text-sm">
                        <input type="radio" name="has_disability" value="1" {{ old('has_disability', $student->has_disability) ? 'checked' : '' }}> Yes
                    </label>
                    <label class="flex items-center gap-1 text-sm">
                        <input type="radio" name="has_disability" value="0" {{ !old('has_disability', $student->has_disability) ? 'checked' : '' }}> No
                    </label>
                </div>
                <input type="text" name="disability_type" value="{{ old('disability_type', $student->disability_type) }}" placeholder="If yes, specify type"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <h3 class="text-green-800 font-bold text-sm uppercase tracking-wide border-b border-green-100 pb-2 mb-4">Current Address</h3>
                <div class="grid sm:grid-cols-3 gap-4 mb-4">
                    <input type="text" name="current_house_no" value="{{ old('current_house_no', $student->current_house_no) }}" placeholder="House No." required
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="current_street" value="{{ old('current_street', $student->current_street) }}" placeholder="Sitio/Street" required
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="current_barangay" value="{{ old('current_barangay', $student->current_barangay) }}" placeholder="Barangay" required
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="grid sm:grid-cols-3 gap-4">
                    <input type="text" name="current_city" value="{{ old('current_city', $student->current_city) }}" placeholder="Municipality/City" required
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="current_province" value="{{ old('current_province', $student->current_province) }}" placeholder="Province" required
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="current_zip" value="{{ old('current_zip', $student->current_zip) }}" placeholder="Zip Code" required
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between border-b border-green-100 pb-2 mb-4">
                    <h3 class="text-green-800 font-bold text-sm uppercase tracking-wide">Permanent Address</h3>
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" id="sameAsCurrent" name="same_as_current" value="1"
                               {{ old('same_as_current', $student->same_as_current) ? 'checked' : '' }}>
                        Same as current address
                    </label>
                </div>
                <div id="permanentFields" class="grid sm:grid-cols-3 gap-4">
                    <input type="text" name="permanent_house_no" value="{{ old('permanent_house_no', $student->permanent_house_no) }}" placeholder="House No."
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="permanent_street" value="{{ old('permanent_street', $student->permanent_street) }}" placeholder="Street Name"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="permanent_barangay" value="{{ old('permanent_barangay', $student->permanent_barangay) }}" placeholder="Barangay"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="permanent_city" value="{{ old('permanent_city', $student->permanent_city) }}" placeholder="Municipality/City"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="permanent_province" value="{{ old('permanent_province', $student->permanent_province) }}" placeholder="Province"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="permanent_zip" value="{{ old('permanent_zip', $student->permanent_zip) }}" placeholder="Zip Code"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div>
                <h3 class="text-green-800 font-bold text-sm uppercase tracking-wide border-b border-green-100 pb-2 mb-4">Parent/Guardian Information</h3>
                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" placeholder="Father's Name"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="father_contact" value="{{ old('father_contact', $student->father_contact) }}" placeholder="Father's Contact Number"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}" placeholder="Mother's Maiden Name"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="mother_contact" value="{{ old('mother_contact', $student->mother_contact) }}" placeholder="Mother's Contact Number"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" placeholder="Guardian's Name"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}" placeholder="Guardian's Contact Number"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 rounded-lg transition">
                Submit for Approval
            </button>
        </form>
    </div>

    {{-- Change password form (instant, no approval) --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-4">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Change Password</h2>
        <form method="POST" action="{{ route('student.profile.password') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Current Password</label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <button type="button" onclick="togglePassword('current_password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('current_password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="new_password" id="new_password" required minlength="8"
                            class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <button type="button" onclick="togglePassword('new_password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required minlength="8"
                            class="w-full px-3 py-2.5 pr-10 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <button type="button" onclick="togglePassword('new_password_confirmation', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @error('new_password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition">
                Update Password
            </button>
        </form>
    </div>

    {{-- Last decision --}}
    @if($lastRequest)
        <div class="mt-4 text-xs text-gray-400">
            Last information update was <span class="{{ $lastRequest->status === 'approved' ? 'text-green-600' : 'text-red-500' }} font-medium">{{ $lastRequest->status }}</span>
            on {{ $lastRequest->reviewed_at?->format('F j, Y') }}
            @if($lastRequest->status === 'rejected' && $lastRequest->admin_remarks) — "{{ $lastRequest->admin_remarks }}" @endif
        </div>
    @endif

</div>

<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';

        btn.innerHTML = isHidden
            ? `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.584 10.587a2 2 0 002.828 2.83M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.847 3.362M6.228 6.228A10.05 10.05 0 002.458 12c1.274 4.057 5.064 7 9.542 7a9.47 9.47 0 004.635-1.223" />
               </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
               </svg>`;
    }

    function toggleEdit(show) {
        document.getElementById('infoSummary').style.display = show ? 'none' : 'block';
        document.getElementById('infoEditForm').style.display = show ? 'block' : 'none';
    }

    const sameCheckbox = document.getElementById('sameAsCurrent');
    const permanentFields = document.getElementById('permanentFields');

    function togglePermanentFields() {
        permanentFields.style.display = sameCheckbox.checked ? 'none' : 'grid';
    }

    sameCheckbox.addEventListener('change', togglePermanentFields);
    togglePermanentFields();
</script>
@endsection