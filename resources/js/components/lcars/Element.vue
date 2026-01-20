<template>
    <div
        :class="classes"
        class="justify-end relative m-0 box-border flex w-[7.5rem] flex-row border-t-2 border-black pr-[0.75rem] pl-[0.75rem] text-right font-bold text-black"
        :style="{
            backgroundColor: props.background,
            '--element-height': elementHeight
        }"
    ></div>
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
const classes = computed(() => {
    let baseClasses = props.classList ? props.classList.join(' ') : '';
    if (props.button) {
        baseClasses +=
            ' cursor-pointer hover:brightness-90 active:brightness-75';
    }
    baseClasses += ' h-[var(--element-height)]';
    return baseClasses;
});

const elementHeight = computed(() => {
    return props.height ? `${3 * props.height}rem` : '3rem';
});
</script>

<style scoped></style>
