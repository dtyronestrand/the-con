<template>
 <div class="service-container w-full flex flex-row flex-wrap">
        <div class="w-[100%] mr-8 min-h-[200px] h-[50%] max-h-full border-3 border-(--indigo)">
        <BarWithTitle classList="top" :background="'var(--indigo)'">Category 1</BarWithTitle>
        <button v-if="edit" class="text-white pt-4 pl-4" type="button" @click="showModal = true">Add Service</button>
        <div class="ml-4 pt" v-for="service in page.props.services" :key="service.id">
        <Button :classList="['round']" background="var(--blue)" :button="true">{{ service.name }}</Button>
        </div>
            <div v-if="showModal" class="service-modal w-max fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 flex">
        <Bracket :classList="['left', 'hollow' ]" background="var(--indigo)"/>
                <form @submit.prevent="addService" class="p-8 bg-black border border-black">
                    <button type="button" @click="showModal = false" class="float-right text-white mb-4">✕</button>
                    <div class="mb-4">
                        <label for="service-name" class="block text-white mb-2">Service Name:</label>
                        <input v-model="form.name" name="name" type="text" id="service-name" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mb-8">
                        <label for="service-url" class="block text-white mb-2">Service URL:</label>
                        <input v-model="form.url" type="url" name="url" id="service-url" class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <button type="submit"  class="bg-(--blue) text-white px-4 py-2 rounded">Add Service</button>
                </form>
                <Bracket :classList="['right', 'hollow' ]" background="var(--indigo)"/>
            </div>
            <div v-if="showModal" class="fixed inset-0 z-40 backdrop" @click="showModal = false"></div>
        </div>
        </div>
</template>

<script setup lang="ts">

</script>

<style scoped>

</style>