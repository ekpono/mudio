<template>
    <div v-for="(comment, commentIdx) in comments" :key="comment.id" class="flex space-x-4 text-sm text-gray-500">
        <div class="flex-none py-5">
            <img :src="comment.avatarSrc" alt="" class="h-10 w-10 rounded-full" />
        </div>
        <div :class="[commentIdx === 0 ? '' : 'border-t border-gray-200', 'flex-1 py-5']">
            <div class="flex justify-between">
                <div class="font-medium text-gray-900 h-3">{{ comment.author }}</div>
                <div v-if="comment.author_id === $page.props.auth.user?.id" @click="$emit('delete:comment', comment)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 cursor-pointer text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>
            </div>
            <div class="prose prose-sm mt-2 max-w-none text-gray-500" v-html="comment.content" />
            <div>
                <div @click="showReply(comment)" class="mt-2 p-1 cursor-pointer hover:bg-blue-300 text-blue-500 hover:inline-flex hover:rounded">
                    Reply
                </div>
            </div>
            <div class="justify-between flex" v-if="comment.reply_count">
                <div @click="getReplies(comment)" class="mt-2 p-1 cursor-pointer hover:bg-blue-300 text-blue-500 hover:inline-flex hover:rounded">
                    {{comment.reply_count}} Replies
                </div>
            </div>
            <create-reply v-if="showCreateReply[comment.id]" :comment="comment"/>
            <view-replies v-if="showViewReplies[comment.id]" :replies="replies" />
        </div>
    </div>

</template>

<script setup>
import ViewReplies from "@/Components/Comment/ViewReplies.vue";
import { ref, toRef, onMounted, defineProps, defineEmits } from "vue";
import CreateReply from "@/Components/Comment/CreateReply.vue";
import axios from "axios";
const props = defineProps(['comments', 'media'])
defineEmits(['delete:comment'])

const comments = toRef(props, 'comments');
const replies = ref([]);
const showCreateReply = ref({});
const showViewReplies = ref({});

const getReplies = (comment) => {
    if (showViewReplies.value[comment.id] === true) {
        showViewReplies.value[comment.id] = false;
        return;
    }
    axios.get(`/media/comments/${comment.id}/reply`).then(res => {
        replies.value = res.data.data
        showViewReplies.value[comment.id] = true;
    })
}

const showReply = (comment) => {
    if (showCreateReply.value[comment.id] === true) {
        showCreateReply.value[comment.id] = false
        return;
    }
    showCreateReply.value[comment.id] = true
}

</script>

<style scoped>

</style>
