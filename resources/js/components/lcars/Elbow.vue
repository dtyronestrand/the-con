<template>
    <div
        :class="classList"
        class="elbow h- relative m-0 min-h-18 w-19 min-w-19 border-t-2 border-ink after:w-[1rem] md:w-38 md:min-w-38 after:md:w-[2rem]"
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
.elbow:after {
    content: '';
    position: absolute;
    height: 3rem;
    background: var(--ink);
}
.left-bottom {
    border-top-left-radius: 3.75rem;
}
.left-bottom:after {
    right: 0;
    top: 1.5rem;
    border-top-left-radius: 1875rem;
}
.left-top {
    border-bottom-left-radius: 3.75rem;
}
.left-top:after {
    right: 0;
    bottom: 1.5rem;
    border-bottom-left-radius: 3.75rem;
}
</style>
