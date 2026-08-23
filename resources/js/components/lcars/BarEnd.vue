<template>
    <div
        :class="props.classList"
        class="relative m-0 h-6 w-6 max-w-6 min-w-6 text-on-surface after:absolute after:block after:bg-ink after:content-['']"
        :style="`background-color: ${props.background || 'var(--panel-secondary)'}`"
    ></div>
</template>

<script setup lang="ts">
interface Props {
    background?: string;
    classList?: string;
}
const props = defineProps<Props>();
</script>

<style scoped>
div {
    flex: 1;
    align-self: flex-end;
}
.top {
    align-self: flex-start;
}
.left {
    border-top-left-radius: 0.75rem;
    border-bottom-left-radius: 0.75rem;
}
.right {
    border-top-right-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
}
.left.decorated:after {
    right: 0.25rem;
    width: 0.25rem;
    height: 100%;
    background-color: var(--ink);
}
.right.decorated:after {
    left: 0.25rem;
    width: 0.25rem;
    height: 100%;
    background-color: var(--ink);
}
</style>
