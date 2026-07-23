<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Admin" />

    <AuthenticatedLayout>
        <template #header>Admin</template>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="p-6 text-slate-900">
                You have admin access.
            </div>
        </div>
    </AuthenticatedLayout>
</template>
