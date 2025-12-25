@extends('layouts.app')

@section('title', 'Login - Kazakh Hotels')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 border border-gray-200 dark:border-gray-700">
        <h1 class="text-3xl font-bold mb-6 text-center">Login</h1>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#38b000] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#38b000] focus:border-transparent">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 dark:border-gray-600">
                    <label for="remember" class="ml-2 text-sm">Remember me</label>
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition font-semibold">
                    Login
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-[#38b000] hover:underline">Register</a>
        </p>
    </div>
</div>
@endsection


