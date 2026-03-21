<template>
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold dark:text-white">Quick Notes</h2>
            <button @click="addNote" class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                + New Note
            </button>
        </div>
        
        <div class="flex flex-wrap gap-4 items-start">
            <StickyNoteItem 
                v-for="note in notes" 
                :key="note.id" 
                :note="note" 
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import {router} from '@inertiajs/vue3';
import StickyNoteItem from "@/components/StickyNoteItem.vue";

defineProps<{
    notes: any[]
}>();



const addNote = () => {
    router.post('/sticky-notes/', {
        preserveScroll: true,
   
    });
}
</script>

<style scoped>

</style>