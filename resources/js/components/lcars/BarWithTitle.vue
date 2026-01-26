<template>
    <div :class="props.classList" class="relative text-black h-6 w-full m-0" :style="`background-color: ${props.background || 'var(--bahama-blue)'}`">
    <span class="bg-black text-white uppercase m-0 ml-[0.83333333333333335rem] pl-[0.1666666666666667rem] pr-[0.1666666666666667rem] pb-[0.1666666666666667rem] inline-block text-4xl font-bold h-full">
<slot/>
    </span>
    </div>
</template>

<script setup lang="ts">
interface Props{
    background?: string;
    classList?:string;
    
}
const props = defineProps<Props>();
</script>

<style scoped>
div{
    flex:1;
    align-self:flex-end;
}
.top{
    align-self:flex-start;
}

</style>