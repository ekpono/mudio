<script setup>
import {
    ChevronDownIcon,
    MagnifyingGlassIcon,
    FolderIcon,
    UsersIcon,
    FilmIcon
} from "@heroicons/vue/20/solid";
import {Link, usePage} from '@inertiajs/vue3';
import { ref } from "vue";
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    Bars3CenterLeftIcon,
    BellIcon,
    HomeIcon,
    XMarkIcon,
    UserCircleIcon
} from "@heroicons/vue/24/outline";

const auth = !! usePage().props.auth.user;
const searchValue = route().params.q

const navigation = [
    { name: "Users", href: "/admin-dashboard", icon: UsersIcon, current: false },
    { name: "Media", href: "/admin-media", icon: FilmIcon, current: true },
    { name: "Report", href: "/admin-report", icon: FolderIcon, current: true },
];
let sidebarOpen = ref(false);
</script>
<template>
    <div class="min-h-full">
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog
                as="div"
                class="relative z-40 lg:hidden"
                @close="sidebarOpen = false"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>
                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel
                            class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button
                                        type="button"
                                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebarOpen = false"
                                    >
                                        <span class="sr-only"
                                        >Close sidebar</span
                                        >
                                        <XMarkIcon
                                            class="h-6 w-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex flex-shrink-0 items-center px-4">
                                <img
                                    class="h-8 w-auto"
                                    src="https://tailwindi.com/img/logos/mark.svg?color=purple&shade=500"
                                    alt="Your Company"
                                />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="w-14 flex-shrink-0" aria-hidden="true">
                        <!-- Dummy element to force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
        <div
            class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-gray-200 lg:bg-gray-100 lg:pt-5 lg:pb-4"
        >
            <Link href="/">
                <div class="flex flex-shrink-0 items-center px-6">
                    <img
                        class="h-8 w-auto"
                        src="https://tailwindui.com/img/logos/mark.svg?color=purple&shade=500"
                        alt="Logo - Ul-Media"
                    />
                </div>
            </Link>
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="mt-5 flex h-0 flex-1 flex-col overflow-y-auto pt-1">
                <!-- User account dropdown -->
                <Menu as="div" class="relative inline-block px-3 text-left" >
                    <div>
                        <MenuButton
                            v-if="auth"
                            class="group w-full rounded-md bg-gray-100 px-3.5 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-100"
                        >
                            <span
                                class="flex w-full items-center justify-between"
                            >
                                <span
                                    class="flex min-w-0 items-center justify-between space-x-3"
                                >
                                    <img
                                        class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300"
                                        src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                                        alt=""
                                    />
                                    <span class="flex min-w-0 flex-1 flex-col">
                                        <span
                                            class="truncate text-sm font-medium text-gray-900"
                                        >{{ $page.props.auth.user.name }}</span
                                        >
                                        <span
                                            class="truncate text-sm text-gray-500"
                                        >{{ $page.props.auth.user.email }}</span
                                        >
                                    </span>
                                </span>
                            </span>
                        </MenuButton>
                    </div>
                </Menu>
                <!-- Navigation -->
                <nav class="mt-6 px-3">
                    <div class="space-y-1"
                         v-for="item in navigation"
                         :key="item.name"
                    >
                        <Link
                            :href="item.href"
                            :class="[
                                $page.url.includes(item.href)
                                    ? 'bg-gray-200 text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900',
                                'group flex items-center rounded-md px-2 py-2 text-sm font-medium',
                            ]"
                            :aria-current="item.current ? 'page' : undefined"
                        >
                            <component
                                :is="item.icon"
                                :class="[
                                    $page.url === item.href
                                        ? 'text-gray-500'
                                        : 'text-gray-400 group-hover:text-gray-500',
                                    'mr-3 h-6 w-6 flex-shrink-0',
                                ]"
                                aria-hidden="true"
                            />
                            {{ item.name }}
                        </Link>
                    </div>
                </nav>
            </div>
        </div>
        <div class="flex flex-1 flex-col lg:pl-64">
            <div
                class="flex h-16 top-0 sticky flex-shrink-0 border-b border-gray-200 bg-white lg:border-none"
            >
                <button
                    type="button"
                    class="border-r border-gray-200 px-4 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3CenterLeftIcon class="h-6 w-6" aria-hidden="true" />
                </button>
                <!-- Search bar -->
                <div
                    class="flex flex-1 justify-between px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8"
                >
                    <div class="flex flex-1 ">
                        <form
                            class="flex w-full md:ml-0"
                            action="#"
                            method="GET"
                        >
                            <label for="search-field" class="sr-only"
                            >Search</label
                            >
                            <div
                                class="relative w-full text-gray-400 focus-within:text-gray-600"
                            >
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center"
                                    aria-hidden="true"
                                >
                                    <MagnifyingGlassIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </div>
                                <input
                                    id="search-field"
                                    name="q"
                                    class="block h-full w-full border-transparent py-2 pl-8 pr-3 text-gray-900 focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm"
                                    placeholder="Search media files"
                                    type="search"
                                    v-model="searchValue"
                                />
                            </div>
                        </form>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <button
                            type="button"
                            class="rounded-full bg-white p-1 text-gray-400 hover:text-gray-500"
                            v-if="auth"
                        >
                            <span class="sr-only">View notifications</span>
                            <div>
                                <BellIcon class="h-6 w-6" aria-hidden="true"  />
                            </div>
                        </button>

                        <Link v-else :href="route('login')" type="button" class="flex items-center border p-2 border-indigo-600 rounded-full bg-white text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            <UserCircleIcon class="h-6 w-6 text-indigo-600 mr-1" aria-hidden="true" />
                            Sign In
                        </Link>
                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-3">
                            <div v-if="auth">
                                <MenuButton
                                    class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50"
                                >
                                    <img
                                        class="h-8 w-8 rounded-full"
                                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                        alt=""
                                    />
                                    <span
                                        class="ml-3 hidden text-sm font-medium text-gray-700 lg:block"
                                    ><span class="sr-only"
                                    >Open user menu for </span
                                    >{{ $page.props.auth.user.name }}</span
                                    >
                                    <ChevronDownIcon
                                        class="ml-1 hidden h-5 w-5 flex-shrink-0 text-gray-400 lg:block"
                                        aria-hidden="true"
                                    />
                                </MenuButton>
                            </div>
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <MenuItem v-slot="{ active }">
                                        <a
                                            :href="route('profile.edit')"
                                            :class="[
                                                active ? 'bg-gray-100' : '',
                                                'block px-4 py-2 text-sm text-gray-700',
                                            ]"
                                        >Your Profile</a
                                        >
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('admin.logout')"
                                            method="post"
                                            :class="[
                                                active ? 'bg-gray-100' : '',
                                                'block px-4 py-2 text-sm text-gray-700',
                                            ]"
                                        >Logout</Link>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
            <main class="flex-1 pb-8">
                <slot />
            </main>
        </div>
    </div>
</template>
