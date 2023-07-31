<template>
    <Head title="Media - Dashboard" />

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
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900">Reports</h1>
                        <p class="mt-2 text-sm text-gray-700">A list of all the media report.</p>
                    </div>
                </div>
                <form class="mt-5 sm:flex sm:items-center"
                      action="#"
                      method="GET"
                      @submit="getReport"
                >
                    <div class="w-full sm:max-w-xs">
                        <label for="email" class="sr-only">Search</label>
                        <input type="text" name="q" v-model="queryParam" autofocus class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Search..." />
                    </div>
                    <button type="submit" @click="getReport" class="mt-3 inline-flex w-full items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:ml-3 sm:mt-0 sm:w-auto">Search</button>
                </form>

                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">#ID</th>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">User Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Reason</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Flag Type</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Video Id</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="flag in flags" :key="flag.id">
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ flag?.id }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ flag?.user.name }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ flag.reason }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ flag.flag_type.name }}</td>
                                    <Link :href="`watch/${flag.id}`">
                                        <td class="whitespace-nowrap px-3 py-5 text-sm text-blue-500">
                                            {{ flag.media.id }}
                                        </td>
                                    </Link>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>

    <InfiniteLoading @infinite="getReport">
        <template #spinner>
            <!--             <span>-->
            <!--                 <Loading :loading="true" /></span>-->
            <div>Loading</div>
        </template>
        <template #complete>
            <span />
        </template>
    </InfiniteLoading>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Greetings from "@/Components/Greetings.vue";
import InfiniteLoading from "v3-infinite-loading"
import "v3-infinite-loading/lib/style.css"
import DashboardLayout from "@/Layouts/Admin/DashboardLayout.vue";
import axios from "axios";
import {ref} from "vue";
const isLoading = ref(false)
const page = ref(1);
const flags = ref([]);


const getReport = async ($state) => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/api/admin/report?page=${page.value}`)
        const json = await response.data.data
        isLoading.value = false
        if (json.length < 1) $state.complete()
        else {
            flags.value.push(...json)
            $state.loaded()
        }
        page.value++
    } catch (error) {
        isLoading.value = false
    }
}
</script>

<style scoped>

</style>
