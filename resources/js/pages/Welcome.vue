<template>
    <div class="grid-container">
    <div class="divider3">
    <div class="flex">
           <Elbow classList="left-bottom " background="var(--anakiwa)"/>
           <BarWithTitle classList="top" :background="'var(--indigo)'">The Con</BarWithTitle><Bar classList="top" :background="'var(--blue)'"/><BarEnd classList="right decorated top" :background="'var(--indigo)'"/>
</div>
    </div>
    <div class="header">
        <Element background="var(--indigo)" :button="false" :height="2"/>
       <Element background="var(--periwinkle)" :button="false"/>
       <Element background="var(--blue)" :button="false"/>
        <Element background="var(--periwinkle)" :button="false"/>
    </div>
    <div class="divider1">
        <Elbow classList="left-top" background="var(--indigo)" />
        <Bar :background="'var(--blue)'"/><BarEnd classList="right decorated" :background="'var(--blue)'"/>
    </div>
    <div class="content1">
    <div>
    <Button :classList="['round']" background="var(--cosmic)" :button="true">Configure</Button>
    <Button :classList="['round']" background="var(--anakiwa )" :button="true"></Button>
    </div>
    <WeatherWidget />
    </div>
    <div class="divider2">
    <Elbow classList="left-bottom" background="var(--anakiwa)" />
    <Bar class="top"  background="var(--indigo)"/><BarEnd classList="right decorated top"  background="var(--indigo)"/>
    </div>
    </div>
</template>

<script setup lang="ts">
    import Bar from '@/components/lcars/Bar.vue';
import BarEnd from '@/components/lcars/BarEnd.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
    import Button from '@/components/lcars/Button.vue';
import Elbow from '@/components/lcars/Elbow.vue';
import Element from '@/components/lcars/Element.vue';
import WeatherWidget from '@/components/WeatherWidget.vue';
</script>

<style scoped>
</style>
