<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600">
                        You are logged in as a <span class="font-semibold text-blue-600">Teacher</span>. 
                        Current Term: <span class="font-semibold">Term {{ $stats['current_term'] ?? 1 }}, {{ date('Y') }}</span>
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">My Classes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ count($stats['classes']) }}</p>
                            </div>
                            <div class="bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            {{ $stats['total_students'] }} total students
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Subjects</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_subjects'] }}</p>
                            </div>
                            <div class="bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Marks</p>
                                <p class="text-2xl font-semibold {{ $stats['pending_marks'] > 0 ? 'text-yellow-600' : 'text-green-600' }}">
                                    {{ $stats['pending_marks'] }}
                                </p>
                            </div>
                            <div class="bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            @if($stats['pending_marks'] > 0)
                                <span class="text-yellow-600">Needs attention</span>
                            @else
                                <span class="text-green-600">All caught up!</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classes and Performance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- My Classes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Classes</h3>
                        <div class="space-y-3">
                            @forelse($stats['classes'] as $class)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <span class="font-medium">Grade {{ $class['grade'] }} {{ $class['class'] }}</span>
                                        <span class="text-sm text-gray-600 ml-2">{{ $class['students'] }} students</span>
                                    </div>
                                    <a href="#" class="text-blue-600 hover:text-blue-900 text-sm">Enter Marks →</a>
                                </div>
                            @empty
                                <p class="text-gray-500">No classes assigned</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Subject Performance Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Subject Performance (Term {{ $stats['current_term'] ?? 1 }})</h3>
                        <div class="space-y-3">
                            @forelse($stats['performance_summary'] as $subject)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>{{ $subject['subject'] }}</span>
                                        <span class="font-medium">{{ $subject['average'] }}% ({{ $subject['grade'] }})</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $subject['average'] }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">No performance data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Entries -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Mark Entries</h3>
                        <a href="{{ route('teacher.marks.index') }}" class="text-sm text-blue-600 hover:text-blue-900">View All →</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($stats['recent_entries'] as $entry)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $entry['student_name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $entry['subject'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry['score'] }}%</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if(in_array($entry['grade'], ['EE1', 'EE2'])) bg-green-100 text-green-800
                                                @elseif(in_array($entry['grade'], ['ME1', 'ME2'])) bg-blue-100 text-blue-800
                                                @elseif(in_array($entry['grade'], ['AE1', 'AE2'])) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $entry['grade'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $entry['date'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No recent entries</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('teacher.marks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                    Enter New Marks
                </a>
                <a href="{{ route('teacher.marks.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                    View All Marks
                </a>
                <a href="{{ route('teacher.reports.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                    Generate Reports
                </a>
            </div>
        </div>
    </div>
</x-app-layout>