<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Profile') }} - {{ $student->full_name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('teacher.students.edit', $student) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                    Edit Student
                </a>
                <a href="{{ route('teacher.reports.show', $student) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                    View Report
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Student Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Student Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Full Name</p>
                            <p class="font-medium text-gray-900">{{ $student->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Date of Birth</p>
                            <p class="font-medium text-gray-900">{{ $student->date_of_birth->format('d M Y') }} ({{ $student->age }} years)</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Gender</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($student->gender) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Nationality</p>
                            <p class="font-medium text-gray-900">{{ $student->nationality }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Admission No.</p>
                            <p class="font-medium text-gray-900">{{ $student->admission_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">UPI Number</p>
                            <p class="font-medium text-gray-900">{{ $student->upi_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Birth Cert No.</p>
                            <p class="font-medium text-gray-900">{{ $student->birth_certificate_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($student->is_graduated) bg-purple-100 text-purple-800
                                @elseif($student->is_active) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $student->is_graduated ? 'Graduated' : ($student->is_active ? 'Active' : 'Inactive') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">School Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Current Grade</p>
                            <p class="font-medium text-gray-900">Grade {{ $student->current_grade_level }} {{ $student->current_class }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Enrollment Year</p>
                            <p class="font-medium text-gray-900">{{ $student->enrollment_year }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Graduation Year</p>
                            <p class="font-medium text-gray-900">{{ $student->graduation_year ?? 'Not Graduated' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Student Contact</h4>
                            <div class="space-y-2">
                                <p><span class="text-sm text-gray-600">Phone:</span> <span class="font-medium">{{ $student->phone ?? 'N/A' }}</span></p>
                                <p><span class="text-sm text-gray-600">Email:</span> <span class="font-medium">{{ $student->email ?? 'N/A' }}</span></p>
                                <p><span class="text-sm text-gray-600">Address:</span> <span class="font-medium">{{ $student->address ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Parent/Guardian</h4>
                            <div class="space-y-2">
                                <p><span class="text-sm text-gray-600">Name:</span> <span class="font-medium">{{ $student->parent_name }}</span></p>
                                <p><span class="text-sm text-gray-600">Relationship:</span> <span class="font-medium">{{ $student->parent_relationship }}</span></p>
                                <p><span class="text-sm text-gray-600">Phone:</span> <span class="font-medium">{{ $student->parent_phone }}</span></p>
                                <p><span class="text-sm text-gray-600">Email:</span> <span class="font-medium">{{ $student->parent_email ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Records Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Academic Records</h3>
                        <a href="{{ route('teacher.reports.show', $student) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            View Full Report →
                        </a>
                    </div>

                    @if($records->isEmpty())
                        <p class="text-gray-500 text-center py-4">No academic records found for this student.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($records as $year => $terms)
                                        @foreach($terms as $term => $termRecords)
                                            @foreach($termRecords as $index => $record)
                                                @php
                                                    $score = $record->score;
                                                    $grade = match(true) {
                                                        $score >= 90 => 'EE1',
                                                        $score >= 75 => 'EE2',
                                                        $score >= 58 => 'ME1',
                                                        $score >= 41 => 'ME2',
                                                        $score >= 31 => 'AE1',
                                                        $score >= 21 => 'AE2',
                                                        $score >= 11 => 'BE1',
                                                        default => 'BE2',
                                                    };
                                                    $gradeColor = match(true) {
                                                        $score >= 90 => 'bg-green-100 text-green-800',
                                                        $score >= 75 => 'bg-green-50 text-green-700',
                                                        $score >= 58 => 'bg-blue-100 text-blue-800',
                                                        $score >= 41 => 'bg-blue-50 text-blue-700',
                                                        $score >= 31 => 'bg-yellow-100 text-yellow-800',
                                                        $score >= 21 => 'bg-yellow-50 text-yellow-700',
                                                        $score >= 11 => 'bg-orange-100 text-orange-800',
                                                        default => 'bg-red-100 text-red-800',
                                                    };
                                                @endphp
                                                <tr>
                                                    @if($index == 0)
                                                        <td rowspan="{{ count($termRecords) }}" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 align-top">
                                                            {{ $year }}
                                                        </td>
                                                        <td rowspan="{{ count($termRecords) }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 align-top">
                                                            Term {{ $term }}
                                                        </td>
                                                    @endif
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                        {{ $record->learningArea->name ?? 'Unknown' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $score }}%</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $gradeColor }}">
                                                            {{ $grade }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('teacher.students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                    ← Back to Students
                </a>
            </div>
        </div>
    </div>
</x-app-layout>