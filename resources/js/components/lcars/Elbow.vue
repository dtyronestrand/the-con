<template>
    <div :class="classList" class="elbow relative w-38 min-w-38 h- min-h-18 m-0 border-t-2 border-black" :style="props.background ? `background-color: ${props.background}` : 'blue'">


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
.elbow:after{
    content: '';
    position: absolute;
    width: 2rem;
    height:3rem;
    background: var(--background);
}
.left-bottom{
    border-top-left-radius: 3.75rem;
}
.left-bottom:after{
    right:0;
    top:1.5rem;
    border-top-left-radius: 1875rem;
}
.left-top{
    border-bottom-left-radius: 3.75rem;
}
.left-top:after{
    right:0;
    bottom:1.5rem;
    border-bottom-left-radius: 3.75rem;
}
</style>