<script setup>
import { computed, ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import SidebarLink from '@/Components/SidebarLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingSidebar = ref(false);
const page = usePage();

const user = computed(() => page.props.auth.user);
const roleName = computed(() => user.value?.role?.name ?? 'user');

const navigation = [
    {
        name: 'Dashboard',
        route: 'dashboard',
        active: 'dashboard',
    },
    {
        name: 'Projects',
        route: 'projects.index',
        active: 'projects.*',
    },
    {
        name: 'Settings',
        route: 'settings.index',
        active: 'settings.*',
    },
];

watch(
    () => page.url,
    () => {
        showingSidebar.value = false;
    },
);
</script>

<template>
    <div class="min-h-screen bg-slate-100">
        <!-- Mobile overlay -->
        <div
            v-show="showingSidebar"
            class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"
            @click="showingSidebar = false"
        />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-slate-900 transition-transform duration-200 ease-in-out lg:translate-x-0',
                showingSidebar ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-800 px-5">
                <Link :href="route('dashboard')" class="flex items-center gap-3">
                    <ApplicationLogo class="block h-8 w-auto fill-current text-white" />
                    <span class="text-sm font-semibold tracking-wide text-white">
                        SaaS Starter
                    </span>
                </Link>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <SidebarLink
                    v-for="item in navigation"
                    :key="item.name"
                    :href="route(item.route)"
                    :active="route().current(item.active)"
                >
                    <template #icon>
                        <!-- Dashboard -->
                        <svg
                            v-if="item.name === 'Dashboard'"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"
                            />
                        </svg>
                        <!-- Projects -->
                        <svg
                            v-else-if="item.name === 'Projects'"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a3 3 0 002.122.88H18A2.25 2.25 0 0120.25 9v.776"
                            />
                        </svg>
                        <!-- Settings -->
                        <svg
                            v-else
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </template>
                    {{ item.name }}
                </SidebarLink>
            </nav>

            <div
                v-if="user?.role?.name === 'admin'"
                class="border-t border-slate-800 px-3 py-4"
            >
                <SidebarLink
                    :href="route('admin.dashboard')"
                    :active="route().current('admin.*')"
                >
                    <template #icon>
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                            />
                        </svg>
                    </template>
                    Admin
                </SidebarLink>
            </div>
        </aside>

        <!-- Main column -->
        <div class="lg:pl-64">
            <!-- Top bar -->
            <header
                class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200 bg-white px-4 sm:px-6"
            >
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 lg:hidden"
                    @click="showingSidebar = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                        />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <div
                        v-if="$slots.header"
                        class="truncate text-lg font-semibold text-slate-800"
                    >
                        <slot name="header" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <div class="text-sm font-medium text-slate-800">
                            {{ user?.name }}
                        </div>
                        <div class="text-xs capitalize text-slate-500">
                            {{ roleName }}
                        </div>
                    </div>

                    <span
                        class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium capitalize text-slate-700 sm:hidden"
                    >
                        {{ roleName }}
                    </span>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-2.5 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-800 text-xs font-semibold uppercase text-white"
                                >
                                    {{ user?.name?.charAt(0) }}
                                </span>
                                <svg
                                    class="hidden h-4 w-4 text-slate-400 sm:block"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="border-b border-slate-100 px-4 py-3 sm:hidden">
                                <div class="text-sm font-medium text-slate-800">
                                    {{ user?.name }}
                                </div>
                                <div class="text-xs capitalize text-slate-500">
                                    {{ roleName }}
                                </div>
                            </div>
                            <DropdownLink :href="route('profile.edit')">
                                Profile
                            </DropdownLink>
                            <DropdownLink :href="route('settings.index')">
                                Settings
                            </DropdownLink>
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Main content -->
            <main class="min-h-[calc(100vh-4rem)] p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
