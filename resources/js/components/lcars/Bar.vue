<template>
    <div
        :class="props.classList"
        class="relative m-0 h-6 w-full text-black"
        :style="`background-color: ${props.background || 'var(--bahama-blue)'}`"
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
</style>
