<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                <div class="mb-6 border-b pb-4">
                    <h1 class="text-2xl font-bold text-gray-900">My Teaching Assignments</h1>
                    <p class="text-gray-600 mt-2">Update the classes and subjects you are teaching for the selected academic year.</p>
                </div>
                
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('teacher.settings.update') }}">
                    @csrf
                    
                    <!-- Academic Year -->
                    <div class="mb-6">
                        <x-input-label for="academic_year_id" value="Academic Year" />
                        <select id="academic_year_id" name="academic_year_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ $year->id == $currentYearId ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Classes Grouped by Grade Level -->
                    <div class="mb-8">
                        <x-input-label value="My Classes" />
                        <p class="text-sm text-gray-500 mb-4">Select the specific class arms you are assigned to.</p>
                        
                        <div class="space-y-6">
                            @foreach($gradeLevels as $gradeLevel)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <!-- Grade Header -->
                                    <div class="bg-gray-100 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                        <h3 class="font-bold text-gray-800">
                                            {{ $gradeLevel->name }}
                                        </h3>
                                        <span class="text-xs font-semibold px-2 py-1 rounded bg-blue-100 text-blue-800">
                                            {{ $gradeLevel->stage }}
                                        </span>
                                    </div>
                                    
                                    <!-- Class Arms List -->
                                    <div class="p-4 bg-white grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @forelse($gradeLevel->classArms as $arm)
                                            <label class="flex items-center space-x-2 cursor-pointer p-2 hover:bg-gray-50 rounded">
                                                <input type="checkbox" 
                                                       name="class_arm_ids[]" 
                                                       value="{{ $arm->id }}" 
                                                       class="rounded text-blue-600 h-4 w-4"
                                                       {{ in_array($arm->id, $assignedClassIds) ? 'checked' : '' }}>
                                                
                                                <!-- UPDATED LINE: Combines Grade Name + Arm Name -->
                                                <span class="text-gray-700 text-sm">
                                                    {{ $gradeLevel->name }} {{ $arm->name }}
                                                </span>
                                            </label>
                                        @empty
                                            <p class="col-span-full text-sm text-gray-400 italic px-2">No classes created for this grade yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <x-input-error :messages="$errors->get('class_arm_ids')" class="mt-2" />
                    </div>

                    <!-- Subjects -->
                    <div class="mb-6">
                        <x-input-label for="subject_ids" value="My Subjects" />
                        <p class="text-sm text-gray-500 mb-2">
                            Select the subjects you teach. 
                            <span class="italic">If you are a Form Teacher, you can leave this blank.</span>
                        </p>
                        <div class="mt-2 grid grid-cols-2 gap-2 bg-gray-50 p-4 rounded-md max-h-60 overflow-y-auto border border-gray-200">
                            @foreach($subjects as $subject)
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" 
                                           class="rounded text-blue-600 h-4 w-4"
                                           {{ in_array($subject->id, $assignedSubjectIds) ? 'checked' : '' }}>
                                    <span class="text-gray-700 text-sm">{{ $subject->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('teacher.dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                            ← Back to Dashboard
                        </a>
                        <x-primary-button>
                            Save Changes
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>