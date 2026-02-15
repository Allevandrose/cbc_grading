<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Report') }} - {{ $student->full_name }}
            </h2>
            <a href="{{ route('teacher.reports.pdf', $student) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Download PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Student Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-medium">{{ $student->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Admission Number</p>
                            <p class="font-medium">{{ $student->admission_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">UPI Number</p>
                            <p class="font-medium">{{ $student->upi_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Grade/Class</p>
                            <p class="font-medium">Grade {{ $student->current_grade_level }} {{ $student->current_class }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Gender</p>
                            <p class="font-medium">{{ ucfirst($student->gender) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date of Birth</p>
                            <p class="font-medium">{{ $student->date_of_birth->format('d M Y') }} ({{ $student->age }} years)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Records -->
            @forelse($records as $year => $terms)
                @foreach($terms as $term => $termRecords)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                Term {{ $term }}, {{ $year }}
                                <span class="ml-4 text-sm font-normal text-gray-600">
                                    Average: {{ $averages[$year][$term]['average'] }}% ({{ $averages[$year][$term]['grade'] }})
                                </span>
                            </h3>
                            
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($termRecords as $record)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $record->learningArea->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $record->score }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($record->score >= 75) bg-green-100 text-green-800
                                                    @elseif($record->score >= 58) bg-blue-100 text-blue-800
                                                    @elseif($record->score >= 41) bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $record->score >= 90 ? 'EE1' : 
                                                       ($record->score >= 75 ? 'EE2' : 
                                                       ($record->score >= 58 ? 'ME1' : 
                                                       ($record->score >= 41 ? 'ME2' : 
                                                       ($record->score >= 31 ? 'AE1' : 
                                                       ($record->score >= 21 ? 'AE2' : 
                                                       ($record->score >= 11 ? 'BE1' : 'BE2'))))) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        No academic records found for this student.
                    </div>
                </div>
            @endforelse

            <!-- Back Button -->
            <div class="mt-4">
                <a href="{{ route('teacher.reports.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    ← Back to Reports
                </a>
            </div>
        </div>
    </div>
</x-app-layout>