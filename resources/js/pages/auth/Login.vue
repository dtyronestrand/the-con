<template>
    <div
        class="login flex h-[100dvh] flex-col items-center justify-center gap-8 py-32"
    >
        <h1 class="font-display text-5xl text-on-surface uppercase">Login</h1>
        <div
            class="flex h-100 w-100 flex-row gap-4 rounded-[1.5rem] bg-surface-raised"
        >
            <Bracket
                :classList="['left', 'hollow']"
                background="var(--panel-secondary-strong)"
            />
            <form
                @submit.prevent="handleSubmit"
                class="flex w-full flex-col items-center justify-center gap-4"
            >
                <label for="email" class="self-start text-on-surface"
                    >Email:</label
                >
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                    class="w-full border border-border bg-surface p-2 text-on-surface"
                />
                <label for="password" class="self-start text-on-surface"
                    >Password:</label
                >
                <input
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                    class="w-full border border-border bg-surface p-2 text-on-surface"
                />
                <Button type="submit" classList="round">Login</Button>
            </form>
            <Bracket
                :classList="['right', 'hollow']"
                background="var(--panel-secondary-strong)"
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
