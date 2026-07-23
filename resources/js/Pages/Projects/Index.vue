<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>Projects</template>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="p-6 text-slate-900">
                <h3 class="text-base font-semibold text-slate-800">
                    Projects
                </h3>
                <p class="mt-2 text-sm text-slate-600">
                    Your projects will show up here.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
