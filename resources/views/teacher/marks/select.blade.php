<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Class and Subject') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('teacher.marks.create') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Grade Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Grade</label>
                                <select name="grade" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Choose Grade</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->grade }}" {{ request('grade') == $grade->grade ? 'selected' : '' }}>
                                            Grade {{ $grade->grade }} ({{ $grade->stage }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subject Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Subject</label>
                                <select name="subject" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Choose Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }} ({{ $subject->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Term Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Term</label>
                                <select name="term" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Choose Term</option>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->term_number }}" {{ request('term') == $term->term_number ? 'selected' : '' }}>
                                            {{ $term->name }} {{ $term->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <a href="{{ route('teacher.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Continue to Marks Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>