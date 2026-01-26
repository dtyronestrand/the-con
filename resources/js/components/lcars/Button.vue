<template>
    <button type="button"
        @click="$emit('click')"
        :class="classes"
        class="justify-end relative m-0 box-border flex w-30 h-12 flex-row border
        my-4 pr-3 pl-3 text-right font-bold text-white  items-center"
        :style="`background-color: rgba(from ${props.background || 'white'} R G B); border-color: ${props.background || 'white'};`"
    ><slot/></button>

</template>

<script setup lang="ts">
import { computed } from 'vue';
interface Props {
    background?: string;
    classList?: string[];
    button: boolean;
    target?: string;
}
const props = withDefaults(defineProps<Props>(), {
    button: true,
});
const emit=defineEmits<{
    (e: 'click'): void;
}>();
const classes = computed(() => {
    let baseClasses = props.classList ? props.classList.join(' ') : '';
    if (props.button) {
        baseClasses +=
            ' cursor-pointer hover:brightness-90 active:brightness-75';
    }
    return baseClasses;
});


</script>

<style scoped>
    .right-round{
        border-top-right-radius: 1.5rem;
        border-bottom-right-radius: 1.5rem;
        padding-right: 1.5rem;
    }
    .left-round{
        border-top-left-radius: 1.5rem;
        border-bottom-left-radius: 1.5rem;
        padding-left: 1.5rem;
    }
    .round{
        border-radius:1.5rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

</style>
