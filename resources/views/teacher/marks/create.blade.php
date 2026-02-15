<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enter Marks') }} - Grade {{ $request->grade }} {{ $subject->name }} (Term {{ $request->term }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('teacher.marks.bulk-store') }}">
                        @csrf
                        <input type="hidden" name="grade" value="{{ $request->grade }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="term" value="{{ $request->term }}">

                        <div class="mb-4">
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                                <p>Enter marks for <strong>{{ count($students) }}</strong> students. Leave blank if not applicable.</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admission No.</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score (0-100)</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $student)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $student->admission_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $student->full_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="number" 
                                                           name="marks[{{ $student->id }}]" 
                                                           value="{{ old('marks.'.$student->id, $existingMarks[$student->id]->score ?? '') }}"
                                                           class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                           min="0" max="100" step="0.01">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if(isset($existingMarks[$student->id]))
                                                        <span class="text-green-600 font-semibold">✓ Entered</span>
                                                    @else
                                                        <span class="text-gray-400">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('teacher.marks.select') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Save Marks') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>