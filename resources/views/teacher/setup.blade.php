<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Onboarding') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Welcome!</h1>
                    <p class="text-gray-600">Please complete your profile setup to access your dashboard.</p>
                </div>
                
                <form method="POST" action="{{ route('teacher.setup.store') }}">
                    @csrf
                    
                    <div class="mb-8">
                        <x-input-label for="academic_year_id" value="Select Current Academic Year" class="font-bold text-lg mb-2" />
                        <select id="academic_year_id" name="academic_year_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ $year->is_current ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('academic_year_id')" class="mt-2" />
                    </div>

                    <hr class="my-8 border-gray-100">

                    <div class="mb-8">
                        <x-input-label value="Select Your Assigned Classes" class="font-bold text-lg mb-1" />
                        <p class="text-sm text-gray-500 mb-4">Select all the specific class streams/arms you teach.</p>
                        
                        <div class="space-y-6 bg-gray-50 p-6 rounded-xl max-h-96 overflow-y-auto border border-gray-100">
                            @foreach($gradeLevels as $grade)
                                <div class="pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                                    <span class="text-sm font-black text-blue-700 uppercase tracking-wider block mb-3">
                                        {{ $grade->name }}
                                    </span>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($grade->classArms as $arm)
                                            <label class="relative flex items-center p-3 rounded-lg border border-gray-200 bg-white hover:bg-blue-50 cursor-pointer transition-colors">
                                                <input type="checkbox" name="class_arm_ids[]" value="{{ $arm->id }}" 
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                <span class="ml-3 text-sm font-medium text-gray-700">
                                                    {{ $arm->name }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('class_arm_ids')" class="mt-2" />
                    </div>

                    <hr class="my-8 border-gray-100">

                    <div class="mb-8">
                        <x-input-label for="subject_ids" value="Select Your Learning Areas (Subjects)" class="font-bold text-lg mb-1" />
                        <p class="text-sm text-gray-500 mb-4">Which subjects do you teach in the classes selected above?</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-gray-50 p-6 rounded-xl max-h-64 overflow-y-auto border border-gray-100">
                            @foreach($subjects as $subject)
                                <label class="flex items-center p-2 hover:bg-white rounded-md transition-all">
                                    <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" 
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-gray-700">{{ $subject->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                        <x-primary-button class="px-8 py-3">
                            {{ __('Complete My Setup') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>