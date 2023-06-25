<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import InfiniteLoading from "v3-infinite-loading"
import "v3-infinite-loading/lib/style.css"
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { ref} from "vue";
import { Link } from '@inertiajs/vue3';
import CreateComment from "@/Components/Comment/CreateComment.vue";
import ViewComment from "@/Components/Comment/ViewComment.vue";
import axios from "axios";
import { HandThumbDownIcon, HandThumbUpIcon} from "@heroicons/vue/20/solid";
import { HandThumbUpIcon as OutlineHandThumbUpIcon, HandThumbDownIcon as OutlineHandThumbDownIcon } from "@heroicons/vue/24/outline";
import FlagAndReport from "@/Components/Media/FlagAndReport.vue";
import Alert from "@/Components/Notifications/Alert.vue";

const media = ref(usePage().props.media.data);
const liked = ref(media.value.is_liked);
const disliked = ref(media.value.is_disliked)

const mostViewed = ref(usePage().props.most_viewed.data);
const comments = ref([]);
const isCommentLoading = ref(false);

const isAuthenticated = !! usePage().props.auth.user;

const unauthTitle = ref('');
const unauthDescription = ref('')
const showAuthModal = ref(false)


const addNewcomment = (comment) => {
    comments.value.unshift(comment)
}
const page = ref(1)

const getComments = async ($state) => {
    isCommentLoading.value = true;
    try {
        const response = await axios.get(`/${media.value.id}/comments?page=${page.value}`)
        const json = await response.data.data
        isCommentLoading.value = false
        if (json.length < 1) $state.complete()
        else {
            comments.value.push(...json)
            $state.loaded()
        }
        page.value++
    } catch (error) {
        console.log(error)
        isCommentLoading.value = false
    }
}

const deleteComment = (comment) => {
    comments.value = comments.value.filter(c => c.id !== comment.id);

    axios.delete(`/media/comments/${comment.id}`)
}

const toggleLike = () => {
    if (! isAuthenticated ) {
        unauthTitle.value = 'Like this video?'
        unauthDescription.value = 'Sign in to make your opinion count.'
        showAuthModal.value = true
        return;
    }
    if (liked.value) {
        liked.value = false;
        sendLikeRequest();
    } else {
        liked.value = true;
        disliked.value = false;
        sendLikeRequest();
    }
};

const toggleDislike = () => {
    if (! isAuthenticated ) {
        unauthTitle.value = 'Don\'t like this video?'
        unauthDescription.value = 'Sign in to make your opinion count.'
        showAuthModal.value = true
        return;
    }
    if (disliked.value) {
        disliked.value = false;
        liked.value = false;
        sendDislikeRequest();
    } else {
        liked.value = false;
        disliked.value = true;
        sendDislikeRequest()
    }
};

const sendLikeRequest = () => {
    axios
        .post(`/media/${media.value.id}/like`)
        .then((response) => {
            // Handle success if needed
        })
        .catch((error) => {
            // Handle error if needed
        });
};

const sendDislikeRequest = () => {
    axios
        .post(`/media/${media.value.id}/dislike`)
        .then((response) => {
            // Handle success if needed
        })
        .catch((error) => {
            // Handle error if needed
        });
};

</script>
<template>
    <Head title="Video Name Goes Here" />
    <DashboardLayout>
        <!-- Page header -->
        <div class="bg-gray-50">
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
                                            <div class="flex justify-between">
                                                <div>{{ media.views }} views &middot {{ media.date_created}}</div>
                                                <div class="flex">
                                                    <div class=" flex mr-2 cursor-pointer hover:bg-gray-300 rounded p-1" @click="toggleLike">
                                                        <HandThumbUpIcon v-if="liked" class="h-5 w-5" />
                                                        <OutlineHandThumbUpIcon v-else class="h-5 w-5" />
                                                        {{ media.count_like }}
                                                    </div>
                                                    |
                                                    <div class="ml-2 cursor-pointer hover:bg-gray-300 rounded p-1" @click="toggleDislike">
                                                        <HandThumbDownIcon v-if="disliked" class="h-5 w-5" />
                                                        <OutlineHandThumbDownIcon v-else class="h-5 w-5" />
                                                    </div>
                                                    <div class="ml-2">
                                                        <flag-and-report :media="media" />
                                                    </div>
                                                </div>
                                            </div>
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
                                    <create-comment :media="media" @comment:added="addNewcomment" />
                                    <view-comment :media="media" :comments="comments" @delete:comment="deleteComment" />

                                    <InfiniteLoading @infinite="getComments">
                                        <template #spinner>
                                            <!--                <span><Loading :loading="true" /></span>-->
                                            <div>Loading</div>
                                        </template>
                                        <template #complete>
                                            <span />
                                        </template>
                                    </InfiniteLoading>
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
        <Alert
            :show="showAuthModal"
            :title="unauthTitle"
            :description="unauthDescription"
        />
    </DashboardLayout>
</template>
<style scoped>
video {
    /* override other styles to make responsive */
    width: 100% !important;
    height: auto !important;
}
</style>
