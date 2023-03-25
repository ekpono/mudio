<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {ref} from "vue";
import { Link } from '@inertiajs/vue3';
const media = ref(usePage().props.media.data)
const mostViewed = ref(usePage().props.most_viewed.data)
</script>
<template>
    <Head title="Video Name Goes Here" />
    <DashboardLayout>
        <!-- Page header -->
        <div class="bg-gray-50 h-screen">
            <div class="sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8">
                <div
                    class="py-6 md:items-center md:justify-between lg:border-t lg:border-gray-200"
                >
                    <main class="mt-10 pb-8">
                        <div class="mx-auto">
                            <h1 class="sr-only">Page title</h1>
                            <!-- Main 3 column grid -->
                            <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3 lg:gap-8">
                                <!-- Left column -->
                                <div class="grid grid-cols-1 gap-4 lg:col-span-2"
                                >
                                    <section aria-labelledby="section-1-title">
                                        <h2
                                            class="sr-only"
                                            id="section-1-title"
                                        ></h2>
                                        <div
                                            class="overflow-hidden rounded-lg shadow"
                                        >
                                            <div
                                                class="w-full h-full object-fill thumbnail"
                                            >
                                                <video controls style="height: 20rem!important;" :poster="media.poster">
                                                    <source
                                                        :src="media.path"
                                                        type="video/mp4"
                                                    />
                                                    Your browser does not
                                                    support HTML video.
                                                </video>
                                            </div>
                                        </div>
                                        <div class="video-details mt-3">
                                            <h1 class=" font-bold">{{ media.title }}</h1>
                                            <div>{{ media.views }} views &middot {{ media.date_created}}</div>
                                            <div class="-mt-px flex divide-gray-200 mt-3">
                                                <div class="flex w-0 flex-1 space-x-2 items-center">
                                                    <img
                                                        class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300"
                                                        :src="media.user_image"
                                                        alt=""
                                                    />
                                                    <div class="text-sm">
                                                        <div class="text-gray-500 text-sm">
                                                            <p>{{ media.user_name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="description min:h-20 bg-gray-100 p-3 rounded mt-2">
                                                {{media.description}}
                                            </div>
                                        </div>

                                    </section>
                                </div>

                                <!-- Right column -->
                                <div class="grid grid-cols-1 gap-4">
                                    <section aria-labelledby="section-2-title">
                                        <h2
                                            class="sr-only"
                                            id="section-2-title"
                                        >
                                            Section title again
                                        </h2>
                                        <div class="overflow-hidden rounded-lg">
                                            <div class="md:pb-2 md:py-0 px-1">
                                                <h1 class="font-medium">Most Viewed</h1>
                                                <div class="flex gap-4 mt-4 cursor-pointer" v-for="media in mostViewed">
                                                    <div class="lg:w-2/4 ">
                                                        <Link :href="`${media.id}`">

                                                            <img
                                                                class="object-cover h-full max-w-full max-h-full w-[10rem] rounded-lg"
                                                                style="height: 6rem!important; width: 10rem!important;"
                                                                :src="media.poster"
                                                            />
                                                        </Link>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p
                                                            class="text-sm font-semibold"
                                                        >
                                                            {{media.title}}
                                                        </p>
                                                        <p
                                                            class="text-xs text-gray-500 mt-1"
                                                        >
                                                            {{ media.user_name }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">{{ media.views }} views</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
<style scoped>
video {
    /* override other styles to make responsive */
    width: 100% !important;
    height: auto !important;
}
</style>
