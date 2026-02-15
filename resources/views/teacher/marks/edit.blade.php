<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Mark') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-4">
                        <p class="text-sm text-blue-700">
                            <strong>Student:</strong> {{ $mark->student->full_name }}<br>
                            <strong>Subject:</strong> {{ $mark->learningArea->name }}<br>
                            <strong>Grade:</strong> {{ $mark->grade_level }} | <strong>Term:</strong> {{ $mark->term }}, {{ $mark->year }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('teacher.marks.update', $mark) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="score" :value="__('Score (0-100)')" />
                            <x-text-input id="score" class="block mt-1 w-full" type="number" name="score" 
                                         value="{{ old('score', $mark->score) }}" required min="0" max="100" step="0.01" />
                            <x-input-error :messages="$errors->get('score')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('teacher.marks.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Mark') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>