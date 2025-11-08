@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-4 sm:p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
        <h1 class="text-2xl font-semibold text-gray-800">👥 Users</h1>
        <a href="" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
           + Add New User
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters / Search -->
    <div class="sticky top-0 z-20 bg-white shadow-sm border-b mb-4 sm:mb-6 p-4 sm:p-6 rounded-lg">
        <form method="" action="" 
              class="flex flex-col md:flex-row flex-wrap gap-3 items-start md:items-center">

            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="🔍 Search name or email..." 
                   class="border border-gray-300 rounded-xl px-4 py-2 flex-1 min-w-[150px] focus:ring-2 focus:ring-blue-500 focus:outline-none transition">

            <div class="flex flex-row flex-wrap gap-2">
                <button type="submit" 
                        class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 shadow-sm transition">
                    Filter
                </button>
                <a href="{{ route('users.index') }}" 
                   class="text-gray-600 underline hover:text-gray-900 transition px-4 py-2 rounded-xl">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left text-gray-700">
                    <th class="p-3 border-b w-12 text-center">#</th>
                    <th class="p-3 border-b">Name</th>
                    <th class="p-3 border-b">Email</th>
                    <th class="p-3 border-b">Registered</th>
                    <th class="p-3 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border-b text-center font-medium text-gray-700">{{ $index + 1 }}</td>
                    <td class="p-3 border-b">{{ $user->name }}</td>
                    <td class="p-3 border-b">{{ $user->email }}</td>
                    <td class="p-3 border-b">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="p-3 border-b text-center">
                        <div class="flex flex-col sm:flex-row justify-center items-center gap-2">
                            <a href="" class="text-blue-600 hover:underline font-medium">View</a>
                            <a href="" class="text-yellow-600 hover:underline font-medium">Edit</a>
                            <form action="" method="" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline font-medium">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">
                        <i class="fas fa-info-circle me-2"></i> No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-end">
        {{ $users->links() }}
    </div>

</div>
@endsection
