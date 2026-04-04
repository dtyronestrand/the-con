<template>
    <div :class="classList"></div>
</template>

<script setup lang="ts">
interface Props {
    classList?: string[];
    background?: string;
}

defineProps<Props>();
</script>

<style scoped>
div {
    background-color: v-bind(background);
}
.left {
    width: 1.5rem;
    border-top-left-radius: 1.5rem;
    border-bottom-left-radius: 1.5rem;
    border-width: 0.75rem 0 0.75rem 0.25rem;
}
.right {
    width: 1.5rem;
    border-top-right-radius: 1.5rem;
    border-bottom-right-radius: 1.5rem;
    border-width: 0.75rem 0.25rem 0.75rem 0;
}
.hollow {
    background-color: #000;
    border-style: solid;
    border-color: v-bind(background);
}
</style>
