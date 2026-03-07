<template>
    <div
        @click="emit('buttonPressed')"
        :class="classes"
        class="relative m-0 box-border flex md:w-30 grow flex-row justify-end border-t-2 border-black text-right font-bold text-black w-15"
        :style="{
            backgroundColor: resolvedBackground,
            borderColor: resolvedBackground,
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

const resolvedBackground = computed(() => {
    if (!props.background) return undefined;
    if (props.background.startsWith('var(')) {
        const varName = props.background.match(/var\(([^)]+)\)/)?.[1];
        if (varName) {
            return getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
        }
    }
    return props.background;
});
</script>

<style scoped></style>
