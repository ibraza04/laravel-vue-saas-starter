<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <template #header>Settings</template>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="p-6 text-slate-900">
                <h3 class="text-base font-semibold text-slate-800">
                    Settings
                </h3>
                <p class="mt-2 text-sm text-slate-600">
                    Manage your account preferences and profile details.
                </p>
                <Link
                    :href="route('profile.edit')"
                    class="mt-4 inline-flex text-sm font-medium text-slate-800 underline hover:text-slate-600"
                >
                    Edit profile
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
