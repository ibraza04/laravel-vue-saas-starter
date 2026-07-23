<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const projects = ref([]);
const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const loadError = ref('');

const formModalOpen = ref(false);
const deleteModalOpen = ref(false);
const editingProject = ref(null);
const projectToDelete = ref(null);

const form = reactive({
    name: '',
    description: '',
    status: 'active',
});

const formErrors = reactive({
    name: '',
    description: '',
    status: '',
});

const isEditing = computed(() => editingProject.value !== null);
const modalTitle = computed(() =>
    isEditing.value ? 'Edit project' : 'Create project',
);

const resetFormErrors = () => {
    formErrors.name = '';
    formErrors.description = '';
    formErrors.status = '';
};

const resetForm = () => {
    form.name = '';
    form.description = '';
    form.status = 'active';
    resetFormErrors();
};

const fetchProjects = async () => {
    loading.value = true;
    loadError.value = '';

    try {
        const { data } = await axios.get('/api/projects');
        projects.value = data.data ?? data;
    } catch (error) {
        loadError.value =
            error.response?.data?.message ?? 'Failed to load projects.';
    } finally {
        loading.value = false;
    }
};

const openCreateModal = () => {
    editingProject.value = null;
    resetForm();
    formModalOpen.value = true;
};

const openEditModal = (project) => {
    editingProject.value = project;
    form.name = project.name;
    form.description = project.description ?? '';
    form.status = project.status;
    resetFormErrors();
    formModalOpen.value = true;
};

const closeFormModal = (force = false) => {
    if (saving.value && !force) {
        return;
    }

    formModalOpen.value = false;
    editingProject.value = null;
    resetForm();
};

const submitForm = async () => {
    saving.value = true;
    resetFormErrors();

    const payload = {
        name: form.name,
        description: form.description || null,
        status: form.status,
    };

    try {
        if (isEditing.value) {
            const { data } = await axios.put(
                `/api/projects/${editingProject.value.id}`,
                payload,
            );
            const updated = data.data ?? data;
            const index = projects.value.findIndex((p) => p.id === updated.id);
            if (index !== -1) {
                projects.value[index] = updated;
            }
        } else {
            const { data } = await axios.post('/api/projects', payload);
            const created = data.data ?? data;
            projects.value.unshift(created);
        }

        closeFormModal(true);
    } catch (error) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors ?? {};
            formErrors.name = errors.name?.[0] ?? '';
            formErrors.description = errors.description?.[0] ?? '';
            formErrors.status = errors.status?.[0] ?? '';
        }
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (project) => {
    projectToDelete.value = project;
    deleteModalOpen.value = true;
};

const closeDeleteModal = (force = false) => {
    if (deleting.value && !force) {
        return;
    }

    deleteModalOpen.value = false;
    projectToDelete.value = null;
};

const deleteProject = async () => {
    if (!projectToDelete.value) {
        return;
    }

    deleting.value = true;
    const deletedId = projectToDelete.value.id;

    try {
        await axios.delete(`/api/projects/${deletedId}`);
        projects.value = projects.value.filter(
            (project) => project.id !== deletedId,
        );
        closeDeleteModal(true);
    } catch (error) {
        loadError.value =
            error.response?.data?.message ?? 'Failed to delete project.';
        closeDeleteModal(true);
    } finally {
        deleting.value = false;
    }
};

const formatDate = (value) => {
    if (!value) {
        return '—';
    }

    return new Date(value).toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

onMounted(fetchProjects);
</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>Projects</template>

        <div class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-600">
                    Manage your projects — create, update, or remove them.
                </p>
                <PrimaryButton type="button" @click="openCreateModal">
                    New project
                </PrimaryButton>
            </div>

            <div
                v-if="loadError"
                class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                {{ loadError }}
            </div>

            <div
                class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm"
            >
                <div v-if="loading" class="p-6 text-sm text-slate-500">
                    Loading projects…
                </div>

                <div
                    v-else-if="projects.length === 0"
                    class="p-6 text-sm text-slate-500"
                >
                    No projects yet. Create your first one to get started.
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 font-semibold text-slate-700"
                                >
                                    Name
                                </th>
                                <th
                                    class="hidden px-4 py-3 font-semibold text-slate-700 md:table-cell"
                                >
                                    Description
                                </th>
                                <th
                                    class="px-4 py-3 font-semibold text-slate-700"
                                >
                                    Status
                                </th>
                                <th
                                    class="hidden px-4 py-3 font-semibold text-slate-700 sm:table-cell"
                                >
                                    Created
                                </th>
                                <th
                                    class="px-4 py-3 text-right font-semibold text-slate-700"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="project in projects"
                                :key="project.id"
                                class="hover:bg-slate-50/80"
                            >
                                <td class="px-4 py-3 font-medium text-slate-900">
                                    {{ project.name }}
                                </td>
                                <td
                                    class="hidden max-w-xs truncate px-4 py-3 text-slate-600 md:table-cell"
                                >
                                    {{ project.description || '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-md px-2 py-0.5 text-xs font-medium capitalize"
                                        :class="
                                            project.status === 'active'
                                                ? 'bg-emerald-50 text-emerald-700'
                                                : 'bg-slate-100 text-slate-600'
                                        "
                                    >
                                        {{ project.status }}
                                    </span>
                                </td>
                                <td
                                    class="hidden px-4 py-3 text-slate-600 sm:table-cell"
                                >
                                    {{ formatDate(project.created_at) }}
                                </td>
                                <td class="px-4 py-3">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <SecondaryButton
                                            type="button"
                                            @click="openEditModal(project)"
                                        >
                                            Edit
                                        </SecondaryButton>
                                        <DangerButton
                                            type="button"
                                            @click="confirmDelete(project)"
                                        >
                                            Delete
                                        </DangerButton>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create / Edit modal -->
        <Modal :show="formModalOpen" max-width="lg" @close="closeFormModal">
            <form class="p-6" @submit.prevent="submitForm">
                <h2 class="text-lg font-medium text-slate-900">
                    {{ modalTitle }}
                </h2>

                <div class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="name" value="Name" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="formErrors.name" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <InputError
                            class="mt-2"
                            :message="formErrors.description"
                        />
                    </div>

                    <div>
                        <InputLabel for="status" value="Status" />
                        <select
                            id="status"
                            v-model="form.status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="active">Active</option>
                            <option value="archived">Archived</option>
                        </select>
                        <InputError class="mt-2" :message="formErrors.status" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton type="button" @click="closeFormModal">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton type="submit" :disabled="saving">
                        {{ saving ? 'Saving…' : isEditing ? 'Update' : 'Create' }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>

        <!-- Delete confirmation -->
        <Modal :show="deleteModalOpen" max-width="md" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-slate-900">
                    Delete project
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Are you sure you want to delete
                    <span class="font-medium text-slate-800">
                        {{ projectToDelete?.name }}
                    </span>
                    ? This action cannot be undone.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton type="button" @click="closeDeleteModal">
                        Cancel
                    </SecondaryButton>
                    <DangerButton
                        type="button"
                        :disabled="deleting"
                        @click="deleteProject"
                    >
                        {{ deleting ? 'Deleting…' : 'Delete' }}
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
