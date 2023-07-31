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
            <!-- Activity list (smallest breakpoint only) -->
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900">Media</h1>
                        <p class="mt-2 text-sm text-gray-700">A list of all the media uploads.</p>
                    </div>
                </div>
                <form class="mt-5 sm:flex sm:items-center"
                      action="#"
                      method="GET"
                      @submit="getMedia"
                >
                    <div class="w-full sm:max-w-xs">
                        <label for="email" class="sr-only">Search</label>
                        <input type="text" name="q" v-model="queryParam" autofocus class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Search..." />
                    </div>
                    <button type="submit" @click="getMedia" class="mt-3 inline-flex w-full items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:ml-3 sm:mt-0 sm:w-auto">Search</button>
                </form>

                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">#ID</th>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">User Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Title</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">File Type</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Visibility</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Views</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Location</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="medium in media" :key="medium.id">

                                    <Link :href="`watch/${medium.id}`" medium.id>
                                        <td class="whitespace-nowrap px-3 py-5 text-sm text-blue-500">
                                            {{ medium.id }}
                                        </td>
                                    </Link>

                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ medium?.user.name }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ medium.title }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ medium.description }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ medium.file_type }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm">
                                        <span :class="[statuses[medium.visibility], 'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium border']">
                                            {{ medium.visibility }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ medium.total_views }}</td>
                                    <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">{{ medium.location }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>

    <InfiniteLoading @infinite="getMedia">
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
const media = ref([]);
const queryParam = ref(route().params.q ?? '')
const statuses = { public: 'text-green-700 bg-green-50 ring-green-600', private: 'text-white bg-red-400 ring-red-500 border-red-50' }


const getMedia = async ($state) => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/api/admin/media?query=${queryParam.value}&page=${page.value}`)
        const json = await response.data.data
        isLoading.value = false
        if (json.length < 1) $state.complete()
        else {
            media.value.push(...json)
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
