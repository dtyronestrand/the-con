<template>
    <div class="container">
    <div class="header">
       <Element background="#0047AB" :button="false"/>
        <Element background="#0047AB" :button="false" :height="2"/>
       <Element background="#0047AB" :button="false"/>
       <Element background="#0047AB" :button="false"/>
        <Element background="#0047AB" :button="false"/>
    
    </div>
    <div class="divider1">
        <Elbow classList="left-top" background="#0047AB" />
        <Bar :backgrund="'#0047AB'"/>
    </div>
    </div>
</template>

<script setup lang="ts">
    import Bar from '@/components/lcars/Bar.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';

</script>

<style scoped>
</style>
