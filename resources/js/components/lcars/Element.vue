<template>
    <div
        @click="emit('buttonPressed')"
        :class="classes"
        class="relative m-0 box-border flex w-15 grow flex-row justify-end border-t-2 border-ink text-right font-display text-on-surface uppercase tracking-tight md:w-30"
        :style="{
            backgroundColor: props.background,
            borderColor: props.background,
            height: elementHeight,
            minHeight: elementHeight,
        }"
    >
        <span class="text-md mx-auto text-center md:text-xl">
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
    background: 'var(--panel-secondary)',
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
