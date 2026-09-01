<template>
    <div
        :class="props.classList"
        class="relative m-0 h-6 w-full text-ink"
        :style="`background-color: ${props.background || 'var(--panel-secondary)'}`"
    >
        <span
            class="m-0 ml-[0.83333333333333335rem] inline-block h-full bg-ink pr-[0.1666666666666667rem] pb-[0.1666666666666667rem] pl-[0.1666666666666667rem] font-display tracking-tight text-on-surface uppercase"
        >
            <slot />
        </span>
    </div>
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
