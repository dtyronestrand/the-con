<template>
    <div
        @click="emit('buttonPressed')"
        :class="classes"
        class="relative m-0 box-border flex w-30 grow flex-row justify-end border-t-2 border-black text-right font-bold text-black"
        :style="{
            backgroundColor: props.background,
            borderColor: props.background,
            height: elementHeight,
            minHeight: elementHeight,
        }"
    >
        <span class="mx-auto p-4 text-center text-xl">
            <slot />
        </span>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
interface Props {
    background?: string;
    height?: number;
    classList?: string[];
    button: boolean;
    target?: string;
}
const props = withDefaults(defineProps<Props>(), {
    button: true,
});
const emit = defineEmits(['buttonPressed']);
const classes = computed(() => {
    let baseClasses = props.classList ? props.classList.join(' ') : '';
    if (props.button) {
        baseClasses +=
            ' cursor-pointer hover:brightness-90 active:brightness-75';
    }
    return baseClasses;
});

const elementHeight = computed(() => {
    return props.height ? `${4 * props.height}rem` : '4.5rem';
});
</script>

<style scoped></style>
