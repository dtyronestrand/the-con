<template>
    <div class="login flex flex-col gap-8 h-[100dvh] py-32 items-center justify-center">
    <h1 class="text-5xl text-(--blue) font-bold">Login</h1>
        <div class="flex w-100 h-100 flex-row gap-4 bg-black rounded-[1.5rem]">
            <Bracket :classList="['left', 'hollow']" background="var(--blue)" />
            <Form ref="formRef" action="/login" method="post"
                class="flex w-full flex-col items-center justify-center gap-4"
            >
                <label for="email" class="self-start text-white">Email:</label>
                <input
                 name="email"
                    type="email"
                    placeholder="Email"
                    class="w-full rounded border border-gray-300 p-2 text-white"
                />
                <label for="password" class="self-start text-white"
                    >Password:</label>
                <input
                 name="password"
                    type="password"
                    placeholder="Password"
                    class="w-full rounded border border-gray-300 p-2 text-white"
                />
             <Button @click="handleSubmit" background="var(--blue)" :classList="['round']">Login</Button>
                  
            </Form>
            <Bracket :classList="['right', 'hollow']" background="var(--blue)" />
        </div>
    </div>
</template>

<script setup lang="ts">
import Bracket from '@/components/lcars/Bracket.vue';
import Button from '@/components/lcars/Button.vue';
import {Form} from '@inertiajs/vue3';
import {ref} from 'vue';

const formRef = ref();

const handleSubmit = () => {
   formRef.value.submit();
}
</script>

<style scoped>
.login {
    background-image: linear-gradient(in oklch, var(--moonbeam), var(--gray));
}
</style>
