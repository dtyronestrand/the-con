<template>
    <div class="flex flex-row flex-wrap gap-4">
        <div
            v-for="category in props.categories"
            :key="category.id"
            class="service-category"
        >
            <div
                v-if="category.services.length && category.services.length > 0"
                class="service-container grow border-2 bg-slate-800"
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
                    <div
                        v-for="service in category.services"
                        :key="service.id"
                        class="group relative"
                    >
                        <Button
                            classList="round"
                            :background="getServiceColor(service.id)"
                            :button="true"
                            ><a
                                target="_blank"
                                :href="service.url"
                                class="mx-auto text-xl text-black"
                                >{{ service.name }}</a
                            ></Button
                        >
                        <button
                            @click.prevent="editService(service)"
                            class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-gray-700 text-white opacity-0 transition-opacity group-focus-within:opacity-100 group-hover:opacity-100 focus-visible:opacity-100 focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                            title="Edit service"
                            aria-label="Edit service"
                        >
                            <span aria-hidden="true">✎</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div
            v-if="localShowModal"
            class="service-modal fixed top-1/2 left-1/2 z-50 flex w-max -translate-x-1/2 -translate-y-1/2 transform"
        >
            <Bracket
                :classList="['left', 'hollow']"
                background="var(--indigo)"
            />
            <form
                @submit.prevent="submit"
                class="border border-black bg-black p-8"
            >
                <button
                    type="button"
                    @click="closeModal"
                    class="float-right mb-4 text-white focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                    aria-label="Close modal"
                >
                    <span aria-hidden="true">✕</span>
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
                            class="text-3xl text-white focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                            type="button"
                            @click="newCategory = !newCategory"
                            aria-label="Add new category"
                        >
                            <span aria-hidden="true">+</span>
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
                <div class="flex gap-4">
                    <button
                        type="submit"
                        class="rounded bg-(--blue) px-4 py-2 text-white"
                    >
                        {{ editingService ? 'Update' : 'Add' }} Service
                    </button>
                    <button
                        v-if="editingService"
                        type="button"
                        @click="deleteService"
                        class="rounded bg-red-600 px-4 py-2 text-white"
                    >
                        Delete
                    </button>
                </div>
            </form>
            <Bracket
                :classList="['right', 'hollow']"
                background="var(--indigo)"
            />
        </div>
        <div
            v-if="localShowModal"
            class="backdrop fixed inset-0 z-40"
            @click="closeModal"
        ></div>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import BarWithTitle from '@/components/lcars/BarWithTitle.vue';
import Bracket from '@/components/lcars/Bracket.vue';
import Button from '@/components/lcars/Button.vue';
import type { Category, Service } from '@/types';
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
    showModal?: boolean;
    service?: Service;
}>();
const newCategory = ref(false);
const localShowModal = ref(props.showModal ?? false);
const editingService = ref<Service | null>(null);
const emit = defineEmits(['closeModal', 'serviceAdded']);
// Watch for changes to the edit prop
watch(
    () => props.showModal,
    (newValue) => {
        localShowModal.value = newValue ?? false;
    },
);

const form = useForm({
    new_category: null as string | null,
    name: '',
    url: '',
    icon: '',
    category_id: null as number | null,
});
const getServiceColor = (serviceId: number) => {
    return colors[serviceId % colors.length];
};

const closeModal = () => {
    localShowModal.value = false;
    editingService.value = null;
    form.reset();
    form.clearErrors();
};

const editService = (service: Service) => {
    editingService.value = service;
    form.name = service.name;
    form.url = service.url;
    form.category_id = service.category_id;
    localShowModal.value = true;
};

const deleteService = () => {
    if (editingService.value && confirm('Delete this service?')) {
        form.delete(`/services/${editingService.value.id}`, {
            onSuccess: () => {
                emit('serviceAdded');
                editingService.value = null;
                closeModal();
            },
        });
    }
};

const submit = () => {
    if (newCategory.value) {
        form.category_id = null;
    } else {
        form.new_category = null;
    }

    if (editingService.value) {
        form.put(`/services/${editingService.value.id}`, {
            onSuccess: () => {
                emit('serviceAdded');
                editingService.value = null;
                newCategory.value = false;
                closeModal();
            },
        });
    } else {
        form.post('/services', {
            onSuccess: () => {
                emit('serviceAdded');
                newCategory.value = false;
                closeModal();
            },
        });
    }
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
.service-category {
    max-width: 40vw;
    border: 1px solid var(--indigo);
}

.service-container {
    max-width: 30vw;
}
</style>
