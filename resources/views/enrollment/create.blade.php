@extends('layouts.form')

@section('title', 'Student Information Form')

@section('content')
    <h1 class="text-center text-green-900 font-bold text-2xl mb-1">Student Information Form</h1>
    <p class="text-center text-gray-500 text-sm mb-6">Please complete your child's profile — SQNHS STE Program</p>

    <form method="POST" action="{{ route('enroll.store') }}" class="space-y-8">
        @csrf

        <div class="text-sm text-gray-500">
            Grade Level: <span class="font-semibold text-green-800">Grade {{ $gradeLevel }}</span>
        </div>

        {{-- LEARNER INFO --}}
        <div>
            <h2 class="text-green-800 font-bold text-sm uppercase tracking-wide border-b border-green-100 pb-2 mb-4">
                Learner Information
            </h2>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">LRN (if available)</label>
                    <input type="text" name="lrn" value="{{ old('lrn') }}" maxlength="12"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">PSA Birth Certificate No.</label>
                    <input type="text" name="psa_birth_cert_no" value="{{ old('psa_birth_cert_no') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Birthdate</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate') }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Sex</label>
                    <select name="sex" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="" disabled selected>Select</option>
                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Place of Birth</label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-green-800 mb-1">Mother Tongue</label>
                <input type="text" name="mother_tongue" value="{{ old('mother_tongue') }}"
                       class="w-full sm:w-1/3 rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="grid sm:grid-cols-2 gap-6 mb-2">
                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">Belonging to any IP Community?</label>
                    <div class="flex gap-4 items-center">
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_ip" value="1" {{ old('is_ip') == '1' ? 'checked' : '' }}> Yes
                        </label>
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_ip" value="0" {{ old('is_ip', '0') == '0' ? 'checked' : '' }}> No
                        </label>
                    </div>
                    <input type="text" name="ip_specify" value="{{ old('ip_specify') }}" placeholder="If yes, specify"
                           class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-green-800 mb-1">4Ps Beneficiary?</label>
                    <div class="flex gap-4 items-center">
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_4ps" value="1" {{ old('is_4ps') == '1' ? 'checked' : '' }}> Yes
                        </label>
                        <label class="flex items-center gap-1 text-sm">
                            <input type="radio" name="is_4ps" value="0" {{ old('is_4ps', '0') == '0' ? 'checked' : '' }}> No
                        </label>
                    </div>
                    <input type="text" name="household_id" value="{{ old('household_id') }}" placeholder="Household ID, if yes"
                           class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-green-800 mb-1">Learner with Disability?</label>
                <div class="flex gap-4 items-center mb-2">
                    <label class="flex items-center gap-1 text-sm">
                        <input type="radio" name="has_disability" value="1" {{ old('has_disability') == '1' ? 'checked' : '' }}> Yes
                    </label>
                    <label class="flex items-center gap-1 text-sm">
                        <input type="radio" name="has_disability" value="0" {{ old('has_disability', '0') == '0' ? 'checked' : '' }}> No
                    </label>
                </div>
                <input type="text" name="disability_type" value="{{ old('disability_type') }}" placeholder="If yes, specify type"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- CURRENT ADDRESS --}}
        <div>
            <h2 class="text-green-800 font-bold text-sm uppercase tracking-wide border-b border-green-100 pb-2 mb-4">
                Current Address <span class="text-red-500 normal-case font-normal lowercase">(required)</span>
            </h2>
            <div class="grid sm:grid-cols-3 gap-4 mb-4">
                <input type="text" name="current_house_no" value="{{ old('current_house_no') }}" placeholder="House No." required
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="current_street" value="{{ old('current_street') }}" placeholder="Sitio/Street" required
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="current_barangay" value="{{ old('current_barangay') }}" placeholder="Barangay" required
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div class="grid sm:grid-cols-3 gap-4">
                <input type="text" name="current_city" value="{{ old('current_city') }}" placeholder="Municipality/City" required
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="current_province" value="{{ old('current_province') }}" placeholder="Province" required
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="current_zip" value="{{ old('current_zip') }}" placeholder="Zip Code" required
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- PERMANENT ADDRESS --}}
        <div>
            <div class="flex items-center justify-between border-b border-green-100 pb-2 mb-4">
                <h2 class="text-green-800 font-bold text-sm uppercase tracking-wide">Permanent Address</h2>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" id="sameAsCurrent" name="same_as_current" value="1"
                           {{ old('same_as_current', '1') ? 'checked' : '' }}>
                    Same as current address
                </label>
            </div>

            <div id="permanentFields" class="grid sm:grid-cols-3 gap-4 mb-4">
                <input type="text" name="permanent_house_no" value="{{ old('permanent_house_no') }}" placeholder="House No."
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="permanent_street" value="{{ old('permanent_street') }}" placeholder="Street Name"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="permanent_barangay" value="{{ old('permanent_barangay') }}" placeholder="Barangay"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="permanent_city" value="{{ old('permanent_city') }}" placeholder="Municipality/City"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="permanent_province" value="{{ old('permanent_province') }}" placeholder="Province"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="permanent_zip" value="{{ old('permanent_zip') }}" placeholder="Zip Code"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- PARENT/GUARDIAN INFO --}}
        <div>
            <h2 class="text-green-800 font-bold text-sm uppercase tracking-wide border-b border-green-100 pb-2 mb-4">
                Parent/Guardian Information <span class="text-red-500 normal-case font-normal lowercase">(fill at least one — father, mother, or guardian)</span>
            </h2>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <input type="text" name="father_name" value="{{ old('father_name') }}" placeholder="Father's Name"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="father_contact" value="{{ old('father_contact') }}" placeholder="Father's Contact Number"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <input type="text" name="mother_name" value="{{ old('mother_name') }}" placeholder="Mother's Maiden Name"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="mother_contact" value="{{ old('mother_contact') }}" placeholder="Mother's Contact Number"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="Guardian's Name"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="text" name="guardian_contact" value="{{ old('guardian_contact') }}" placeholder="Guardian's Contact Number"
                       class="rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        <button type="submit"
                class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 rounded-lg transition">
            Submit Information
        </button>
    </form>

    <script>
        const sameCheckbox = document.getElementById('sameAsCurrent');
        const permanentFields = document.getElementById('permanentFields');

        function toggleFields() {
            permanentFields.style.display = sameCheckbox.checked ? 'none' : 'grid';
        }

        sameCheckbox.addEventListener('change', toggleFields);
        toggleFields();
    </script>
@endsection