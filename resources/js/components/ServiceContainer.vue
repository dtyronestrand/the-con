<template>
    <div class="flex flex-row flex-wrap gap-4">
        <div
            v-for="category in props.categories"
            class="service-container grow border-2 bg-gray-900"
            style="
                min-height: 12.5rem;
                min-width: 25rem;
                border-color: var(--indigo);
            "
        >
            <BarWithTitle classList="top" :background="'var(--indigo)'"
                >{{ category.name }}
            </BarWithTitle>
            <div class="mx-4 mt-2 flex flex-row flex-wrap gap-4">
                <Button
                    v-for="service in category.services"
                    :key="service.id"
                    :classList="['round']"
                    :background="getServiceColor(service.id)"
                    :button="true"
                    ><a
                        :href="service.url"
                        class="mx-auto text-xl text-black"
                        >{{ service.name }}</a
                    ></Button
                >
            </div>
        </div>

        <!-- Modal outside the loop -->
        <div
            v-if="showModal"
            class="service-modal fixed top-1/2 left-1/2 z-50 flex w-max -translate-x-1/2 -translate-y-1/2 transform"
        >
            <Bracket
                :classList="['left', 'hollow']"
                background="var(--indigo)"
            />
            <pre class="text-xs text-white">{{ form.data() }}</pre>
            <form
                @submit.prevent="submit"
                class="border border-black bg-black p-8"
            >
                <button
                    type="button"
                    @click="emit('closeModal')"
                    class="float-right mb-4 text-white"
                >
                    ✕
                </button>
                <div class="mb-4">
                    <label for="service-category" class="mb-2 block text-white"
                        >Category:</label
                    >
                    <div class="flex flex-row gap-4">
                        <select
                            v-if="props.categories.length && !newCategory"
                            v-model="form.category_id"
                            id="service-category"
                            class="w-full rounded border border-gray-300 p-2 text-white"
                        >
                            <option value="" disabled selected>
                                Select a category
                            </option>

                            <option
                                class="text-black"
                                v-for="cat in props.categories"
                                :value="cat.id"
                                :key="cat.id"
                            >
                                {{ cat.name }}
                            </option>
                        </select>
                        <button
                            class="text-3xl text-white"
                            type="button"
                            @click="newCategory = !newCategory"
                        >
                            +
                        </button>
                    </div>
                    <input
                        v-if="newCategory"
                        v-model="form.new_category"
                        name="new_category"
                        type="text"
                        id="new-category"
                        class="mt-4 w-full rounded border border-gray-300 p-2 text-white"
                        placeholder="New Category Name"
                    />
                </div>
                <div class="mb-4">
                    <label for="service-name" class="mb-2 block text-white"
                        >Service Name:</label
                    >
                    <input
                        v-model="form.name"
                        name="name"
                        type="text"
                        id="service-name"
                        class="w-full rounded border border-gray-300 p-2 text-white"
                        required
                    />
                </div>
                <div class="mb-8">
                    <label for="service-url" class="mb-2 block text-white"
                        >Service URL:</label
                    >
                    <input
                        v-model="form.url"
                        type="url"
                        name="url"
                        id="service-url"
                        class="w-full rounded border border-gray-300 p-2 text-white"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="rounded bg-(--blue) px-4 py-2 text-white"
                >
                    Add Service
                </button>
            </form>
            <Bracket
                :classList="['right', 'hollow']"
                background="var(--indigo)"
            />
        </div>
        <div
            v-if="showModal"
            class="backdrop fixed inset-0 z-40"
            @click="emit('closeModal')"
        ></div>
    </div>
</template>

<script setup lang="ts">
import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Bracket from '@/components/lcars/Bracket.vue';
import Button from '@/components/lcars/Button.vue';
import type { Category } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const colors = [
    'var(--indigo)',
    'var(--blue)',
    'var(--anakiwa)',
    'var(--periwinkle)',
    'var(--cosmic)',
    'var(--moonbeam)',
];
const props = defineProps<{
    categories: Category[];
    edit?: boolean;
}>();
const newCategory = ref(false);
const showModal = ref(props.edit ?? false);
const emit = defineEmits(['closeModal', 'serviceAdded']);
// Watch for changes to the edit prop
watch(
    () => props.edit,
    (newValue) => {
        showModal.value = newValue ?? false;
    },
);
const form = useForm({
    new_category: null,
    name: '',
    url: '',
    icon: '',
    category_id: '',
});
const getServiceColor = (serviceId: number) => {
    return colors[serviceId % colors.length];
};
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
    // If we are making a new category, clear the old ID
    if (newCategory.value) {
        form.category_id = '';
    } else {
        form.new_category = null;
    }

    form.post('/services', {
        onSuccess: () => {
            emit('serviceAdded');
            newCategory.value = false;
            closeModal();
        },
    });
};
</script>

<style scoped>
.backdrop {
    background-color: rgba(from var(--anakiwa) r g b / 0.3);
}

#add-service {
    border-radius: 1.5rem;
}
input {
    background-color: rgba(from var(--indigo) R G B/0.4);
    color: white;
}
.service-container {
    border-color: var(--indigo);
}
</style>
