<template>
    <button
        type="button"
        @click="$emit('click')"
        :class="classes"
        class="relative m-0 my-4 box-border flex h-12 w-30 flex-row border pr-3 pl-3 font-bold"
        :style="{
            backgroundColor: resolvedBackground,
            borderColor: resolvedBackground,
        }"
    >
        <slot />
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';
interface Props {
    background?: string;
    classList?: string;
    target?: string;
}
const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'click'): void;
}>();
const classes = computed(() => {
    let baseClasses = props.classList ? props.classList + ' ' : '';

    baseClasses += ' cursor-pointer hover:brightness-90 active:brightness-75';

    return baseClasses;
});

const resolvedBackground = computed(() => {
    if (!props.background) return 'white';
    if (props.background.startsWith('var(')) {
        const varName = props.background.match(/var\(([^)]+)\)/)?.[1];
        if (varName) {
            return getComputedStyle(document.documentElement)
                .getPropertyValue(varName)
                .trim();
        }
    }
    return props.background;
});
</script>

<style scoped>
.right-round {
    border-top-right-radius: 1.5rem;
    border-bottom-right-radius: 1.5rem;
    padding-right: 1.5rem;
}
.left-round {
    border-top-left-radius: 1.5rem;
    border-bottom-left-radius: 1.5rem;
    padding-left: 1.5rem;
}
.round {
    border-radius: 1.5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}
</style>
