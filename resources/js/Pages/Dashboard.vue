<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import InfiniteLoading from "v3-infinite-loading"
import "v3-infinite-loading/lib/style.css"

import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Greetings from "@/Components/Greetings.vue";
import axios from "axios";
const media = ref([])
const page = ref(1);
const isLoading = ref(false)

const getMedia = async ($state) => {
    isLoading.value = true;
    let queryParam = route().params.q ?? ''
    try {
        const response = await axios.get(`/files?query=${queryParam}&page=${page.value}`)
        const json = await response.data.media
        isLoading.value = false
        if (json.length < 1) $state.complete()
        else {
            media.value.push(...json)
            $state.loaded()
        }
        page.value++
    } catch (error) {
        console.log(error)
        isLoading.value = false
    }
}
const showAlert = ref(false);

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
            <h2
                class="mx-auto mt-8 max-w-6xl px-4 text-lg font-medium leading-6 text-gray-900 sm:px-6 lg:px-8 mb-4"
            >
                Uploads -> All Media
            </h2>
            <!-- Activity list (smallest breakpoint only) -->
            <div class="px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8">
                <ul
                    v-if="media.length > 0"
                    role="list"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <li
                        v-for="file in media"
                        :key="file.email"
                        class="col-span-1 divide-gray-200 rounded-lg bg-white cursor-pointer motion-safe:hover:scale-[1.03]"
                    >
                        <Link :href="`/watch/${file.id}`">
                            <div
                                class="flex w-full items-center justify-between space-x-6"
                            >
                                <img
                                    alt=""
                                    style="background-color: transparent"
                                    class="object-cover rounded-xl w-[30rem] h-[12rem]"
                                    :src="file.poster"
                                />
                            </div>
                            <div>
                                <div class="-mt-px flex divide-gray-200 mt-3">
                                    <div class="flex w-0 flex-1 space-x-2">
                                        <img
                                            class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300"
                                            :src="file.user_image"
                                            alt=""
                                        />
                                        <div class="text-sm">
                                            <p class="font-bold">
                                                {{file.title}}
                                            </p>
                                            <div class="text-gray-500 text-xs">
                                                <p>{{ file.user_name }}</p>
                                                <p>
                                                    {{ file.views }} views &middot {{ file.date_created}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
                <div v-else>
                    <h1>No Upload Found</h1>
                </div>
            </div>
        </div>
        <InfiniteLoading @infinite="getMedia">
            <template #spinner>
                <!--                <span><Loading :loading="true" /></span>-->
                <div>Loading</div>
            </template>
            <template #complete>
                <span />
            </template>
        </InfiniteLoading>
    </DashboardLayout>
</template>

