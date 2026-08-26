<template>
    <div class="flex flex-row flex-wrap gap-4">
        <div
            v-for="category in props.categories"
            :key="category.id"
            class="service-category"
        >
            <div
                v-if="category.services.length && category.services.length > 0"
                class="service-container grow border-2 bg-surface-raised"
                style="
                    min-height: 12.5rem;
                    min-width: 25rem;
                    border-color: var(--panel-primary);
                "
            >
                <BarWithTitle classList="top" :background="'var(--panel-primary)'"
                    >{{ category.name }}
                </BarWithTitle>
                <div class="mx-4 mt-2 flex flex-row flex-wrap gap-4">
                    <div
                        v-for="service in category.services"
                        :key="service.id"
                        class="group relative"
                    >
                        <Button classList="round" :button="true"
                            ><a
                                target="_blank"
                                :href="service.url"
                                class="mx-auto text-sm"
                                >{{ service.name }}</a
                            ></Button
                        >
                        <button
                            @click.prevent="editService(service)"
                            class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-neutral text-on-surface opacity-0 transition-opacity group-hover:opacity-100"
                            title="Edit service"
                        >
                            ✎
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
                background="var(--panel-primary)"
            />
            <form
                @submit.prevent="submit"
                class="border border-border bg-surface-overlay p-8"
            >
                <button
                    type="button"
                    @click="closeModal"
                    class="float-right mb-4 text-on-surface"
                >
                    ✕
                </button>
                <div class="mb-4">
                    <label
                        for="service-category"
                        class="mb-2 block text-on-surface"
                        >Category:</label
                    >
                    <div class="flex flex-row gap-4">
                        <select
                            v-if="props.categories.length && !newCategory"
                            v-model="form.category_id"
                            id="service-category"
                            class="w-full border border-border bg-surface-raised p-2 text-on-surface"
                        >
                            <option value="" disabled selected>
                                Select a category
                            </option>

                            <option
                                class="text-ink"
                                v-for="cat in props.categories"
                                :value="cat.id"
                                :key="cat.id"
                            >
                                {{ cat.name }}
                            </option>
                        </select>
                        <button
                            class="text-3xl text-on-surface"
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
                        class="mt-4 w-full border border-border bg-surface-raised p-2 text-on-surface"
                        placeholder="New Category Name"
                    />
                </div>
                <div class="mb-4">
                    <label for="service-name" class="mb-2 block text-on-surface"
                        >Service Name:</label
                    >
                    <input
                        v-model="form.name"
                        name="name"
                        type="text"
                        id="service-name"
                        class="w-full border border-border bg-surface-raised p-2 text-on-surface"
                        required
                    />
                </div>
                <div class="mb-8">
                    <label for="service-url" class="mb-2 block text-on-surface"
                        >Service URL:</label
                    >
                    <input
                        v-model="form.url"
                        type="url"
                        name="url"
                        id="service-url"
                        class="w-full border border-border bg-surface-raised p-2 text-on-surface"
                        required
                    />
                </div>
                <div class="flex gap-4">
                    <button
                        type="submit"
                        class="bg-tertiary px-4 py-2 text-ink hover:bg-tertiary-strong"
                    >
                        {{ editingService ? 'Update' : 'Add' }} Service
                    </button>
                    <button
                        v-if="editingService"
                        type="button"
                        @click="deleteService"
                        class="bg-error px-4 py-2 text-on-surface"
                    >
                        Delete
                    </button>
                </div>
            </form>
            <Bracket
                :classList="['right', 'hollow']"
                background="var(--panel-primary)"
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
    background-color: rgba(from var(--panel-secondary-subtle) r g b / 0.3);
}

#add-service {
    border-radius: 1.5rem;
}
.service-category {
    max-width: 40vw;
    border: 1px solid var(--panel-primary);
}

.service-container {
    max-width: 30vw;
}
</style>
