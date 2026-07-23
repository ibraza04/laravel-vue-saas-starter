<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center gap-3 rounded-md bg-slate-800 px-3 py-2 text-sm font-medium text-white'
        : 'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800/60 hover:text-white transition',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <span class="flex h-5 w-5 shrink-0 items-center justify-center">
            <slot name="icon" />
        </span>
        <slot />
    </Link>
</template>
