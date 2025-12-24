<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex flex-col justify-center items-center p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Logo Section -->
    <div class="text-center mb-10">
        <div class="flex items-center justify-center space-x-3 mb-4">
            <div
                class="h-14 w-14 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-xl">
                <i class="fas fa-gem text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent">
                    Quartz System</h1>
                <p class="text-gray-600 mt-1">Expense Management Platform</p>
            </div>
        </div>
        <p class="text-gray-500 text-sm max-w-md">Sign in to manage your expenses, categories, and brands efficiently</p>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden">
        <!-- Card Header -->
        <div class="p-8 border-b border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 text-center">Welcome Back</h2>
            <p class="text-gray-600 text-center mt-2">Please sign in to your account</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div
                class="m-6 p-4 rounded-xl bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-200 text-green-700 text-sm">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form wire:submit="login" class="p-8 space-y-6">
            <!-- Email Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="email" class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-envelope text-blue-500 text-sm"></i>
                        <span>Email Address</span>
                    </label>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-user"></i>
                    </div>
                    <input wire:model="form.email" id="email" type="email" name="email" required autofocus
                        autocomplete="username" placeholder="you@example.com"
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-sm">
                </div>

                @error('form.email')
                    <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-lock text-blue-500 text-sm"></i>
                        <span>Password</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="text-xs text-blue-500 hover:text-blue-600 font-medium">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-key"></i>
                    </div>
                    <input wire:model="form.password" id="password" type="password" name="password" required
                        autocomplete="current-password" placeholder="••••••••"
                        class="w-full pl-12 pr-12 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-sm">

                    <button type="button"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        onclick="togglePassword()">
                        <i class="fas fa-eye" id="password-toggle"></i>
                    </button>
                </div>

                @error('form.password')
                    <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="h-5 w-5 rounded border-gray-300 text-blue-500 focus:ring-blue-500 focus:ring-2 transition-colors">
                <label for="remember" class="ms-3 text-sm text-gray-700">
                    Remember me
                </label>
            </div>

            <!-- Login Button -->
            <button type="submit" wire:loading.attr="disabled"
                class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-4 px-6 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2 text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                <i class="fas fa-sign-in-alt"></i>
                <span wire:loading.remove wire:target="login">Sign In</span>
                <span wire:loading wire:target="login" class="flex items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Signing In...</span>
                </span>
            </button>

            <!-- Demo Account Hint (Optional) -->
            <div class="text-center">
                <div
                    class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 text-xs">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>Demo: admin@quartz.com / password</span>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-100 bg-gray-50/50">
            <p class="text-center text-gray-500 text-sm">
                Don't have an account?
                <a href="#" class="text-blue-500 hover:text-blue-600 font-medium ml-1">
                    Contact Administrator
                </a>
            </p>
        </div>
    </div>

    <!-- Copyright -->
    <div class="mt-8 text-center">
        <p class="text-gray-400 text-xs">© {{ date('Y') }} Quartz System. All rights reserved.</p>
    </div>

    <style>
        /* iOS-style form animations */
        input:focus {
            transform: translateY(-1px);
            transition: transform 0.2s ease;
        }

        /* Smooth transitions */
        * {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom checkbox styling */
        input[type="checkbox"]:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
    </style>

    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-focus on email field
        document.addEventListener('livewire:initialized', function() {
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
            }
        });
``
        // Handle Enter key to submit form
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                // Let Livewire handle the form submission
            }
        });
    </script>
</div>
