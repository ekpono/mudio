<script setup>
import {reactive, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DeleteButton from "@/Components/DeleteButton.vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import NotificationAlert from "@/Components/NotificationAlert.vue";
import {Head, Link, usePage, router } from "@inertiajs/vue3";
import ModalDeleteVideo from "@/Components/Media/ModalDeleteVideo.vue";
import ModalEditMedia from "@/Components/Media/ModalEditMedia.vue";
import MediaSummary from "@/Components/Media/MediaSummary.vue";
import InfiniteLoading from "v3-infinite-loading"
import "v3-infinite-loading/lib/style.css"
import axios from "axios";
const mediaSummary = usePage().props.summary
const media = ref([])
const selectedMedia = ref([])
const showAlert = ref(false)
const showDeleteConfirmation = ref(false)
const showEditModal = ref(false)
const isLoading = ref(false)
const page = ref(1)
const getMedia = async ($state) => {
    isLoading.value = true;
    try {
        let queryParam = route().params.q ?? ''
        const response = await axios.get(`/personal/files?query=${queryParam}&page=${page.value}`)
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

const toggleDeleteModal = () => {
    showDeleteConfirmation.value = ! showDeleteConfirmation.value
}
const toggleEditModal = () => {
    showEditModal.value = ! showEditModal.value
}
const deleteSuccess = () => {
    //Do partial reload
    toggleDeleteModal()
    router.reload({ only: ["media", "summary"] })
    media.value = usePage().props.media.data;
}
const activateDeleteModal = (file) => {
    selectedMedia.value = file
    showDeleteConfirmation.value = true
}

const activateEditModal = (file) => {
    selectedMedia.value = file
    showEditModal.value = true
}

const editSuccess = () => {
    toggleEditModal()
}
</script>
<template>
    <Head title="Uploads" />
    <DashboardLayout>
        <!-- Page header -->
        <div class="mt-8">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-lg font-medium leading-6 text-gray-900">
                    My Uploads
                </h2>
                <MediaSummary :mediaSummary="usePage().props.summary" />
            </div>
            <h2
                class="mx-auto mt-8 max-w-6xl px-4 text-lg font-medium leading-6 text-gray-900 sm:px-6 lg:px-8 mb-4"
            >
                Uploads -> All Media
            </h2>
            <!-- Activity list (smallest breakpoint only) -->
            <div class="px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8">
                <NotificationAlert
                    v-if="showAlert"
                    :title="'Successfully uploaded'"
                />
                <ul
                    role="list"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                    v-if="media.length > 0"
                >
                    <li
                        v-for="file in media"
                        :key="file.email"
                        class="col-span-1 divide-gray-200 rounded-lg bg-white cursor-pointer motion-safe:hover:scale-[1.03]"
                    >
                        <Link :href="`/video/${file.id}`">
                            <div
                                class="flex w-full items-center justify-between space-x-6"
                            >
                                <img
                                    alt=""
                                    style="background-color: transparent"
                                    class="rounded-xl w-[30rem] h-[12rem]"
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
                        <div class="mt-5 space-x-3 text-right">
                            <PrimaryButton @click="activateEditModal(file)">Edit</PrimaryButton>
                            <DeleteButton @click="activateDeleteModal(file)"
                            >Delete</DeleteButton>
                        </div>
                    </li>
                </ul>
                <div v-else>
                    <h1>No Upload Found</h1>
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
            <ModalDeleteVideo
                :toggleModal="showDeleteConfirmation"
                @close:modal="toggleDeleteModal"
                @delete:success="deleteSuccess"
                :media="selectedMedia"
            />
            <ModalEditMedia
                :toggleModal="showEditModal"
                :media="selectedMedia"
                @edit:success="editSuccess"
                @close:modal="toggleEditModal"
            />
        </div>
    </DashboardLayout>

</template>
