@extends('layouts.app')

@section('title', 'Account Requests')

@section('content')
<div class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-8 py-4 sm:py-6 lg:py-8">

    <div class="anim-fade bg-gradient-to-br from-green-600 to-green-800 rounded-2xl shadow-lg px-4 sm:px-8 py-8 relative overflow-hidden">
        <svg class="absolute -right-8 -top-8 w-48 h-48 text-white/10 pointer-events-none" viewBox="0 0 64 64" fill="none">
            <circle cx="32" cy="32" r="3" fill="currentColor"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(60 32 32)"/>
            <ellipse cx="32" cy="32" rx="28" ry="11" stroke="currentColor" stroke-width="2" transform="rotate(120 32 32)"/>
        </svg>
        <div class="relative">
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Account Requests</h1>
            <p class="text-green-100 text-sm mt-1">Review student username, email, and password change requests</p>
        </div>
    </div>

    

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-4">
        @forelse($requests as $req)
            <div class="p-6 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-medium text-gray-900">
                            {{ $req->user->student->first_name ?? $req->user->username }}
                            {{ $req->user->student->last_name ?? '' }}
                            <span class="text-xs text-gray-400 font-normal">({{ $req->user->username }})</span>
                        </p>
                        <div class="text-sm text-gray-600 mt-2 space-y-1">
                            @if($req->new_username)
                                <p>New username: <strong>{{ $req->new_username }}</strong></p>
                            @endif
                            @if($req->new_email)
                                <p>New email: <strong>{{ $req->new_email }}</strong></p>
                            @endif
                            @if($req->new_password)
                                <p>Password change requested</p>
                            @endif
                            @if($req->changes)
                                <p>Student information update requested</p>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Submitted {{ $req->created_at->format('F j, Y g:ia') }}</p>
                    </div>
                    <div class="flex flex-col gap-2 shrink-0">
                        <form method="POST" action="{{ route('admin.account-requests.approve', $req) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition w-full">
                                Approve
                            </button>
                        </form>
                        @if($req->changes)
                            <button type="button" id="changes-btn-{{ $req->id }}" onclick="toggleChanges({{ $req->id }})"
                                class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-xs font-medium transition">
                                View Changes
                            </button>
                        @endif
                        <button type="button" onclick="document.getElementById('reject-{{ $req->id }}').classList.toggle('hidden')"
                            class="px-4 py-2 rounded-lg bg-white border border-red-200 hover:bg-red-50 text-red-600 text-xs font-medium transition">
                            Reject
                        </button>
                    </div>
                </div>

                @if($req->changes)
                    <div id="changes-{{ $req->id }}" class="hidden mt-3 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Requested Information</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                            @php
                                $labels = [
                                    'lrn' => 'LRN', 'psa_birth_cert_no' => 'PSA Birth Cert No.',
                                    'first_name' => 'First Name', 'last_name' => 'Last Name', 'middle_name' => 'Middle Name',
                                    'birthdate' => 'Birthdate', 'sex' => 'Sex', 'place_of_birth' => 'Place of Birth',
                                    'mother_tongue' => 'Mother Tongue', 'is_ip' => 'IP Community', 'ip_specify' => 'IP Specify',
                                    'is_4ps' => '4Ps Beneficiary', 'household_id' => 'Household ID',
                                    'has_disability' => 'Has Disability', 'disability_type' => 'Disability Type',
                                    'current_house_no' => 'Current House No.', 'current_street' => 'Current Street',
                                    'current_barangay' => 'Current Barangay', 'current_city' => 'Current City',
                                    'current_province' => 'Current Province', 'current_zip' => 'Current Zip',
                                    'same_as_current' => 'Permanent = Current', 'permanent_house_no' => 'Permanent House No.',
                                    'permanent_street' => 'Permanent Street', 'permanent_barangay' => 'Permanent Barangay',
                                    'permanent_city' => 'Permanent City', 'permanent_province' => 'Permanent Province',
                                    'permanent_zip' => 'Permanent Zip',
                                    'father_name' => 'Father Name', 'father_contact' => 'Father Contact',
                                    'mother_name' => 'Mother Name', 'mother_contact' => 'Mother Contact',
                                    'guardian_name' => 'Guardian Name', 'guardian_contact' => 'Guardian Contact',
                                ];
                            @endphp
                            @foreach($req->changes as $key => $value)
                                <div>
                                    <span class="text-gray-400 text-xs">{{ $labels[$key] ?? $key }}</span>
                                    <p class="text-gray-800 font-medium">
                                        @if(is_bool($value))
                                            {{ $value ? 'Yes' : 'No' }}
                                        @else
                                            {{ $value !== null && $value !== '' ? $value : '—' }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form id="reject-{{ $req->id }}" method="POST" action="{{ route('admin.account-requests.reject', $req) }}" class="hidden mt-3 flex gap-2">
                    @csrf
                    @method('PUT')
                    <input type="text" name="admin_remarks" placeholder="Reason (optional)"
                        class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-medium transition">
                        Confirm Reject
                    </button>
                </form>
            </div>
        @empty
            <div class="px-6 py-16 text-center text-gray-400 text-sm">No pending account requests.</div>
        @endforelse
    </div>

</div>

<style>
    .anim-fade { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function toggleChanges(id) {
        const panel = document.getElementById('changes-' + id);
        const btn = document.getElementById('changes-btn-' + id);
        panel.classList.toggle('hidden');
        btn.textContent = panel.classList.contains('hidden') ? 'View Changes' : 'Close';
    }
</script>
@endsection