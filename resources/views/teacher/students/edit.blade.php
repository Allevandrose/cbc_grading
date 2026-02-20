<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student') }} - {{ $student->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('teacher.students.update', $student) }}">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <x-input-label for="first_name" value="First Name *" />
                                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" 
                                            :value="old('first_name', $student->first_name)" required />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="middle_name" value="Middle Name" />
                                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full" 
                                            :value="old('middle_name', $student->middle_name)" />
                                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="last_name" value="Last Name *" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" 
                                            :value="old('last_name', $student->last_name)" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="date_of_birth" value="Date of Birth *" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" 
                                            :value="old('date_of_birth', $student->date_of_birth->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gender" value="Gender *" />
                                <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nationality" value="Nationality *" />
                                <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full" 
                                            :value="old('nationality', $student->nationality)" required />
                                <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Identification Numbers -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Identification Numbers</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <x-input-label for="admission_number" value="Admission Number *" />
                                <x-text-input id="admission_number" name="admission_number" type="text" class="mt-1 block w-full" 
                                            :value="old('admission_number', $student->admission_number)" required />
                                <x-input-error :messages="$errors->get('admission_number')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="upi_number" value="UPI Number *" />
                                <x-text-input id="upi_number" name="upi_number" type="text" class="mt-1 block w-full" 
                                            :value="old('upi_number', $student->upi_number)" required />
                                <x-input-error :messages="$errors->get('upi_number')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="birth_certificate_number" value="Birth Certificate Number" />
                                <x-text-input id="birth_certificate_number" name="birth_certificate_number" type="text" class="mt-1 block w-full" 
                                            :value="old('birth_certificate_number', $student->birth_certificate_number)" />
                                <x-input-error :messages="$errors->get('birth_certificate_number')" class="mt-2" />
                            </div>
                        </div>

                        <!-- School Information -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">School Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <x-input-label for="current_grade_level" value="Current Grade *" />
                                <select id="current_grade_level" name="current_grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Select Grade</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->grade }}" {{ old('current_grade_level', $student->current_grade_level) == $grade->grade ? 'selected' : '' }}>
                                            Grade {{ $grade->grade }} ({{ $grade->stage }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('current_grade_level')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="current_class" value="Class *" />
                                <x-text-input id="current_class" name="current_class" type="text" class="mt-1 block w-full" 
                                            :value="old('current_class', $student->current_class)" required />
                                <x-input-error :messages="$errors->get('current_class')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="enrollment_year" value="Enrollment Year *" />
                                <x-text-input id="enrollment_year" name="enrollment_year" type="number" class="mt-1 block w-full" 
                                            :value="old('enrollment_year', $student->enrollment_year)" min="2000" max="{{ date('Y') }}" required />
                                <x-input-error :messages="$errors->get('enrollment_year')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <x-input-label for="phone" value="Phone Number" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" 
                                            :value="old('phone', $student->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email Address" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                            :value="old('email', $student->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="address" value="Address" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" 
                                            :value="old('address', $student->address)" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Parent/Guardian Information -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Parent/Guardian Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <x-input-label for="parent_name" value="Parent/Guardian Name *" />
                                <x-text-input id="parent_name" name="parent_name" type="text" class="mt-1 block w-full" 
                                            :value="old('parent_name', $student->parent_name)" required />
                                <x-input-error :messages="$errors->get('parent_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="parent_phone" value="Parent Phone *" />
                                <x-text-input id="parent_phone" name="parent_phone" type="text" class="mt-1 block w-full" 
                                            :value="old('parent_phone', $student->parent_phone)" required />
                                <x-input-error :messages="$errors->get('parent_phone')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="parent_email" value="Parent Email" />
                                <x-text-input id="parent_email" name="parent_email" type="email" class="mt-1 block w-full" 
                                            :value="old('parent_email', $student->parent_email)" />
                                <x-input-error :messages="$errors->get('parent_email')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="parent_relationship" value="Relationship *" />
                                <select id="parent_relationship" name="parent_relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">Select Relationship</option>
                                    <option value="Father" {{ old('parent_relationship', $student->parent_relationship) == 'Father' ? 'selected' : '' }}>Father</option>
                                    <option value="Mother" {{ old('parent_relationship', $student->parent_relationship) == 'Mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="Guardian" {{ old('parent_relationship', $student->parent_relationship) == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                                </select>
                                <x-input-error :messages="$errors->get('parent_relationship')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('teacher.students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg mr-2 transition duration-150">
                                Cancel
                            </a>
                            <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                                {{ __('Update Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>