<x-app-layout x-data="{ showUpload: false }">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Question Bank') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.questions.template') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Download Template
                </a>
                <button @click="showUpload = !showUpload" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Bulk Upload
                </button>
                <a href="{{ route('admin.questions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Add New
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Bulk Upload Form -->
            <div x-show="showUpload" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" style="display: none;">
                <div class="p-6 text-gray-900 border-l-4 border-indigo-500">
                    <h3 class="font-bold text-lg mb-4">Bulk Upload Questions (JSON)</h3>
                    <form action="{{ route('admin.questions.bulk-upload') }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-4">
                        @csrf
                        <div class="flex-1">
                            <label for="file" class="block text-sm font-medium text-gray-700">Select JSON File</label>
                            <input type="file" name="file" id="file" accept=".json,.txt" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                            " required>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded">
                            Upload Questions
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 mt-2">Make sure your JSON matches the template format.</p>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('admin.questions.index') }}" class="flex gap-4 items-end" x-data>
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700">Search Question</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                placeholder="Search by text..."
                                @input.debounce.500ms="$el.form.submit()"
                                onfocus="var val=this.value; this.value=''; this.value=val;" autofocus>
                        </div>
                        <div class="w-48">
                            <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                            <select name="level" id="level" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                @change="$el.form.submit()">
                                <option value="">All Levels</option>
                                <option value="Easy" {{ request('level') == 'Easy' ? 'selected' : '' }}>Easy</option>
                                <option value="Medium" {{ request('level') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Hard" {{ request('level') == 'Hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>
                        @if(request()->has('search') || request()->has('level'))
                            <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-900 underline ml-2 mb-2">Clear</a>
                        @endif
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">XP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($questions as $question)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $question->level === 'Easy' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $question->level === 'Medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $question->level === 'Hard' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $question->level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $question->soal_text }}">
                                            {{ $question->soal_text }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $question->tipe == 'multiple_choice' ? 'Multiple Choice' : 'Short Answer' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $question->jawaban_benar }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $question->bobot_xp }} XP
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        
                                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No questions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $questions->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
