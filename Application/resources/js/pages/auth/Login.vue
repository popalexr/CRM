<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { Mail, Lock, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-zinc-900 to-zinc-800">
        <div class="w-full max-w-md bg-zinc-900 bg-opacity-80 rounded-xl shadow-lg p-8 space-y-6 text-white">
            <div class="flex justify-center">
                <div class="w-20 h-20 rounded-full bg-zinc-700 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.953 9.953 0 0012 20c2.021 0 3.897-.597 5.442-1.615M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            <h2 class="text-center text-2xl font-semibold">Log in to your account</h2>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Email -->
                <div class="space-y-1">
                    <label for="email" class="text-sm">Email ID</label>
                    <div class="relative">
                        <Mail class="absolute left-3 top-3 w-5 h-5 text-zinc-400" />
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            placeholder="you@example.com"
                            required
                            autofocus
                            class="w-full pl-10 pr-4 py-2 rounded-md bg-zinc-800 text-white border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="text-sm">Password</label>
                    <div class="relative">
                        <Lock class="absolute left-3 top-3 w-5 h-5 text-zinc-400" />
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            placeholder="********"
                            required
                            class="w-full pl-10 pr-4 py-2 rounded-md bg-zinc-800 text-white border border-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                </div>

                <!-- Remember / Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" v-model="form.remember" class="form-checkbox bg-zinc-700 text-indigo-500 border-zinc-600 rounded" />
                        Remember me
                    </label>
                    <a :href="route('password.request')" class="text-indigo-400 hover:underline">Forgot Password?</a>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-150"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin inline-block mr-2" />
                    Log in
                </button>
            </form>
        </div>
    </div>
</template>
