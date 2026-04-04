<template>
    <div
        class="login flex h-[100dvh] flex-col items-center justify-center gap-8 py-32"
    >
        <h1 class="text-5xl font-bold text-(--blue)">Login</h1>
        <div class="flex h-100 w-100 flex-row gap-4 rounded-[1.5rem] bg-black">
            <Bracket :classList="['left', 'hollow']" background="var(--blue)" />
            <form
                @submit.prevent="handleSubmit"
                class="flex w-full flex-col items-center justify-center gap-4"
            >
                <label for="email" class="self-start text-white">Email:</label>
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                    class="w-full rounded border border-gray-300 p-2 text-white"
                />
                <label for="password" class="self-start text-white"
                    >Password:</label
                >
                <input
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                    class="w-full rounded border border-gray-300 p-2 text-white"
                />
                <Button type="submit" background="var(--blue)" classList="round"
                    >Login</Button
                >
            </form>
            <Bracket
                :classList="['right', 'hollow']"
                background="var(--blue)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';

import Bracket from '@/components/lcars/Bracket.vue';
import Button from '@/components/lcars/Button.vue';

const form = useForm({
    email: '',
    password: '',
});

const handleSubmit = () => {
    form.post('/login', {
        onSuccess: () => router.get('/'),
    });
};
</script>

<style scoped></style>
