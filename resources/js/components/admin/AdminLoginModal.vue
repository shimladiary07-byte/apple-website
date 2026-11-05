<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-md bg-white dark:bg-stone-900 rounded-3xl shadow-2xl border border-stone-200 dark:border-stone-800 overflow-hidden my-8 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">

      <!-- Close Button -->
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 p-2 text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-xl transition-colors cursor-pointer"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>

      <div class="text-center mb-6">
        <div class="w-14 h-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-2xl mx-auto mb-3 shadow-md">
          {{ authMode === 'login' ? '🔐' : '✨' }}
        </div>
        <h3 class="text-2xl font-bold text-stone-900 dark:text-stone-100 font-serif-title">
          {{ authMode === 'login' ? 'Portal Log In' : 'Create New Account' }}
        </h3>
        <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">
          {{ authMode === 'login' ? 'Access orchard management, rates & orders' : 'Sign up to access the orchard portal' }}
        </p>
      </div>

      <!-- Auth Mode Switch Tabs -->
      <div class="flex p-1 bg-stone-100 dark:bg-stone-800 rounded-2xl mb-5 text-xs font-bold">
        <button
          type="button"
          @click="switchMode('login')"
          :class="[
            'flex-1 py-2 rounded-xl transition-all cursor-pointer text-center',
            authMode === 'login' ? 'bg-white dark:bg-stone-700 text-stone-900 dark:text-stone-100 shadow-xs' : 'text-stone-500 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100'
          ]"
        >
          Sign In / Log In
        </button>
        <button
          type="button"
          @click="switchMode('register')"
          :class="[
            'flex-1 py-2 rounded-xl transition-all cursor-pointer text-center',
            authMode === 'register' ? 'bg-white dark:bg-stone-700 text-stone-900 dark:text-stone-100 shadow-xs' : 'text-stone-500 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-100'
          ]"
        >
          Register / Sign Up
        </button>
      </div>

      <!-- Error / Success Alert -->
      <div v-if="errorMessage" class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
        {{ errorMessage }}
      </div>
      <div v-if="successMessage" class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
        {{ successMessage }}
      </div>

      <!-- 1. LOGIN FORM -->
      <form v-if="authMode === 'login'" @submit.prevent="login" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Email Address</label>
          <input
            v-model="loginForm.email"
            type="email"
            required
            placeholder="you@example.com"
            class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs sm:text-sm outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Password</label>
          <input
            v-model="loginForm.password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs sm:text-sm outline-none"
          />
        </div>

        <button
          type="submit"
          :disabled="isLoading"
          class="w-full py-3 px-4 rounded-xl bg-stone-900 hover:bg-stone-800 dark:bg-stone-800 dark:hover:bg-stone-700 disabled:bg-stone-400 text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer flex items-center justify-center gap-2"
        >
          <svg v-if="isLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
          <span v-if="!isLoading">Log In to Dashboard</span>
          <span v-else>Verifying...</span>
        </button>

        <p class="text-xs text-center text-stone-500 dark:text-stone-400 pt-2">
          Don't have an account yet?
          <button type="button" @click="switchMode('register')" class="text-red-600 dark:text-red-400 font-bold hover:underline cursor-pointer">
            Register here
          </button>
        </p>
      </form>

      <!-- 2. REGISTER FORM -->
      <form v-else @submit.prevent="register" class="space-y-3.5">
        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Full Name *</label>
          <input
            v-model="registerForm.name"
            type="text"
            required
            placeholder="e.g. Ramesh Sharma"
            class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs sm:text-sm outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Email Address *</label>
          <input
            v-model="registerForm.email"
            type="email"
            required
            placeholder="your-name@example.com"
            class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs sm:text-sm outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Password *</label>
          <input
            v-model="registerForm.password"
            type="password"
            required
            minlength="6"
            placeholder="Minimum 6 characters"
            class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs sm:text-sm outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-stone-700 dark:text-stone-300 mb-1">Confirm Password *</label>
          <input
            v-model="registerForm.password_confirmation"
            type="password"
            required
            minlength="6"
            placeholder="Re-enter password"
            class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-700 text-stone-900 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-red-500 text-xs sm:text-sm outline-none"
          />
        </div>

        <button
          type="submit"
          :disabled="isLoading"
          class="w-full py-3 px-4 rounded-xl bg-red-600 hover:bg-red-700 disabled:bg-stone-400 text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer flex items-center justify-center gap-2 mt-2"
        >
          <svg v-if="isLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
          <span v-if="!isLoading">Create Account & Sign In</span>
          <span v-else>Registering...</span>
        </button>

        <p class="text-xs text-center text-stone-500 dark:text-stone-400 pt-2">
          Already registered?
          <button type="button" @click="switchMode('login')" class="text-red-600 dark:text-red-400 font-bold hover:underline cursor-pointer">
            Sign In here
          </button>
        </p>
      </form>

    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      authMode: 'login', // 'login' or 'register'
      loginForm: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      isLoading: false,
      errorMessage: '',
      successMessage: ''
    };
  },
  methods: {
    switchMode(mode) {
      this.authMode = mode;
      this.errorMessage = '';
      this.successMessage = '';
    },
    async login() {
      this.isLoading = true;
      this.errorMessage = '';
      this.successMessage = '';

      try {
        const res = await axios.post('/api/auth/login', {
          email: this.loginForm.email,
          password: this.loginForm.password
        });

        if (res.data.success) {
          this.$emit('logged-in', res.data.user);
          this.$emit('close');
        }
      } catch (err) {
        if (err.response && err.response.data && err.response.data.message) {
          this.errorMessage = err.response.data.message;
        } else {
          this.errorMessage = 'Invalid email or password.';
        }
      } finally {
        this.isLoading = false;
      }
    },
    async register() {
      if (this.registerForm.password !== this.registerForm.password_confirmation) {
        this.errorMessage = 'Passwords do not match. Please verify.';
        return;
      }

      this.isLoading = true;
      this.errorMessage = '';
      this.successMessage = '';

      try {
        const res = await axios.post('/api/auth/register', this.registerForm);

        if (res.data.success) {
          this.successMessage = 'Account created successfully!';
          setTimeout(() => {
            this.$emit('logged-in', res.data.user);
            this.$emit('close');
          }, 600);
        }
      } catch (err) {
        if (err.response && err.response.data && err.response.data.errors) {
          const firstErr = Object.values(err.response.data.errors)[0];
          this.errorMessage = Array.isArray(firstErr) ? firstErr[0] : firstErr;
        } else if (err.response && err.response.data && err.response.data.message) {
          this.errorMessage = err.response.data.message;
        } else {
          this.errorMessage = 'Registration failed. Please try again.';
        }
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>
