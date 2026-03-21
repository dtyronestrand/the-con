<template>
    <div
        :class="props.classList"
        class="relative m-0 h-6 w-full text-black"
        :style="`background-color: ${props.background || 'var(--bahama-blue)'}`"
    >
        <span
            class="m-0 ml-[0.83333333333333335rem] inline-block h-full bg-black pr-[0.1666666666666667rem] pb-[0.1666666666666667rem] pl-[0.1666666666666667rem] font-bold text-white uppercase"
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
