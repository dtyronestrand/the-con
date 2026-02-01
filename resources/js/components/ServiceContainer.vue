<template>
 <div class="flex flex-row gap-4 flex-wrap">
        <div v-for="category in props.categories" class="min-w-100 h-50 border-3 border-(--indigo)">
        <BarWithTitle classList="top" :background="'var(--indigo)'">{{ category.name }} </BarWithTitle>
        <div class="ml-4 mt-8" v-for="service in category.services" :key="service.id">
        <Button :classList="['round']" :background="colors[Math.floor(Math.random() * colors.length)]" :button="true"><a :href="service.url" class="mx-auto text-xl text-black">{{ service.name }}</a></Button>
        </div>

        </div>
        
        <!-- Modal outside the loop -->
        <div v-if="showModal" class="service-modal w-max fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 flex">
            <Bracket :classList="['left', 'hollow' ]" background="var(--indigo)"/>
            <form @submit.prevent="submit" class="p-8 bg-black border border-black">
                <button type="button" @click="showModal = false" class="float-right text-white mb-4">✕</button>
                <div class="mb-4 ">
                    <label for="service-category" class="block text-white mb-2">Category:</label>
                    <div class="flex flex-row gap-4">
                    <select v-if="props.categories.length && !newCategory" v-model="form.category_id" name="category_id" id="service-category" class="w-full p-2 text-white border border-gray-300 rounded" >
                        <option class="text-white" v-for="cat in props.categories" :value="cat.id" :key="cat.id">{{ cat.name }}</option>
                    </select>
                    <button class="text-white text-3xl" type="button" @click="newCategory = !newCategory">+</button>
                    </div>
                    <input v-if="newCategory" v-model="form.new_category" name="new_category" type="text" id="new-category" class="w-full p-2 text-white border border-gray-300 rounded mt-4" placeholder="New Category Name">
                </div>
                <div class="mb-4">
                    <label for="service-name" class="block text-white mb-2">Service Name:</label>
                    <input v-model="form.name" name="name" type="text" id="service-name" class="w-full p-2 text-white border border-gray-300 rounded" required>
                </div>
                <div class="mb-8">
                    <label for="service-url" class="block text-white mb-2">Service URL:</label>
                    <input v-model="form.url" type="url" name="url" id="service-url" class="w-full p-2 text-white border border-gray-300 rounded" required>
                </div>
                <button type="submit"  class="bg-(--blue) text-white px-4 py-2 rounded">Add Service</button>
            </form>
            <Bracket :classList="['right', 'hollow' ]" background="var(--indigo)"/>
        </div>
        <div v-if="showModal" class="fixed inset-0 z-40 backdrop" @click="showModal = false"></div>
        </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import {useForm, Link} from '@inertiajs/vue3';
import Button from '@/components/lcars/Button.vue';
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Bracket from '@/components/lcars/Bracket.vue';
import type {Category} from '@/types';

const colors = ['var(--indigo)', 'var(--blue)', 'var(--anakiwa)', 'var(--periwinkle)', 'var(--cosmic)', 'var(--moonbeam)'];
const props = defineProps<{
    categories: Category[]
    edit?: boolean
}>()
const newCategory = ref(false);
const showModal = ref(props.edit ?? false);
const emit = defineEmits<{
    (e: 'serviceAdded'): void
}>();
// Watch for changes to the edit prop
watch(() => props.edit, (newValue) => {
    showModal.value = newValue ?? false;
});
const form = useForm({
    new_category: null,
    name: '',
    url: '',
    icon: '',
    category_id: '',
});

const openModal = () => {
    form.reset();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    form.post('/services', {
        onSuccess: () => {
            emit('serviceAdded');
            closeModal();
        },
    });
};
</script>

<style scoped>
.backdrop {
    background-color: rgba(from var(--anakiwa) r g b / 0.3);
}


#add-service{
    border-radius:1.5rem;
}
input {
    background-color: rgba(from var(--indigo) R G B/0.4);
    color:white;
}
</style>