<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import {reactive, ref} from "vue";
import InfiniteLoading from "v3-infinite-loading"
import "v3-infinite-loading/lib/style.css"
import NotificationAlert from "@/Components/NotificationAlert.vue";
import DashboardLayout from "@/Layouts/Admin/DashboardLayout.vue";
import Greetings from "@/Components/Greetings.vue";
import axios from "axios";
import ModalCreateUser from "@/Components/User/ModalCreateUser.vue";
const media = ref([])
const page = ref(1);
const isLoading = ref(false)
const users = ref([]);
const queryParam = ref(route().params.q ?? '')
const selectedUser = ref(null);
const editMode = ref(false);
const createModal = ref(false)

const refetchUser = async () => {
    const response = await axios.get(`/api/admin/users?query=${queryParam.value}&page=1`)
    users.value = await response.data.data
}

const toggleCreateUserModal = () => {
    createModal.value = ! createModal.value;
}

const openCreateModal = () => {
    editMode.value = false;
    selectedUser.value = null
    toggleCreateUserModal()
}

const editAction = (person) => {
    selectedUser.value = person;
    editMode.value = true;
    toggleCreateUserModal()
}

const getUsers = async ($state) => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/api/admin/users?query=${queryParam.value}&page=${page.value}`)
        const json = await response.data.data
        isLoading.value = false
        if (json.length < 1) $state.complete()
        else {
            users.value.push(...json)
            $state.loaded()
        }
        page.value++
    } catch (error) {
        isLoading.value = false
    }
}
const showAlert = ref(false);
const toggleAlertModal = () => {
    showAlert.value = ! showAlert.value
}

const userCreated = () => {
    toggleAlertModal()
    toggleCreateUserModal()
    refetchUser()
}

</script>
<template>
    <Head title="Dashboard" />
    <DashboardLayout>
        <!-- Page header -->
        <div class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8">
                <div
                    class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200"
                >
                    <div class="min-w-0 flex-1">
                        <!-- Profile -->
                        <div class="flex items-center">
                            <div>
                                <div class="flex items-center">
                                    <img
                                        class="h-16 w-16 rounded-full sm:hidden"
                                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.6&w=256&h=256&q=80"
                                        alt=""
                                    />
                                    <div
                                        class="ml-3 text-2xl flex font-bold leading-7 text-gray-900 sm:truncate sm:leading-9"
                                    >
                                        <Greetings />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8">
            <!-- Activity list (smallest breakpoint only) -->
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>
                        <p class="mt-2 text-sm text-gray-700">A list of all the users including their name, title, email and role.</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none" @click="openCreateModal">
                        <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Add user
                        </button>
                    </div>
                </div>
                <form class="mt-5 sm:flex sm:items-center"
                      action="#"
                      method="GET"
                      @submit="getUsers"
                >
                    <div class="w-full sm:max-w-xs">
                        <label for="email" class="sr-only">Search</label>
                        <input type="text" name="q" v-model="queryParam" autofocus class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Search..." />
                    </div>
                    <button type="submit" @click="getUsers" class="mt-3 inline-flex w-full items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:ml-3 sm:mt-0 sm:w-auto">Search</button>
                </form>

                <div class="inline-block mt-5">
                    <NotificationAlert
                        v-if="showAlert"
                        :title="'Successfully uploaded'"
                        @close:alert="toggleAlertModal"
                    />
                </div>

                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Upload</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="person in users" :key="person.email">
                                    <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                        <div class="flex items-center">
                                            <div class="h-11 w-11 flex-shrink-0">
                                                <img class="h-11 w-11 rounded-full" :src="person.image" alt="" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ person.name }}</div>
                                                <div class="mt-1 text-gray-500">{{ person.email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ person.role }}</td>

                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ person.total_upload }}</td>
                                    <td class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                        <button @click="editAction(person)" class="text-indigo-600 hover:text-indigo-900"
                                        >Edit<span class="sr-only">, {{ person.name }}</span></button
                                        >
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ModalCreateUser
            @create-user:success="userCreated"
            @close:modal="toggleCreateUserModal"
            :editMode="editMode"
            :user="selectedUser"
            :showModal="createModal"
            v-if="createModal"
        />
        <InfiniteLoading @infinite="getUsers">
            <template #spinner>
<!--             <span>-->
<!--                 <Loading :loading="true" /></span>-->
                <div>Loading</div>
            </template>
            <template #complete>
                <span />
            </template>
        </InfiniteLoading>
    </DashboardLayout>
</template>

