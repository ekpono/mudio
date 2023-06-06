<template>
    <form  @submit.prevent="submitReply">
        <div>
        <textarea
            @keyup="adjustTextareaHeight"
            v-model="content"
            rows="2"
            id="commentBody"
            ref="comment"
            class="block mb-5 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            placeholder="Add a reply..."
        ></textarea>
        </div>
        <div class="mt-2 flex justify-end">
            <SecondaryButton
                class="mr-3"
            >Cancel</SecondaryButton
            >
            <button type="submit" :class="[ isCommentTyping ? 'bg-indigo-600' : 'bg-indigo-200', 'inline-flex items-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' ]">Reply</button>
        </div>
    </form>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import axios from "axios";
const isCommentTyping = ref(false);
const content = ref('');
const adjustTextareaHeight = () => {
    const textarea = document.getElementById('commentBody');
    textarea.style.height = 'auto';
    textarea.style.height = `${textarea.scrollHeight}px`;
    if (isCommentTyping.value === true) return
    isCommentTyping.value = true
}
const props = defineProps(['comment']);
const emit = defineEmits(['comment:added'])

const submitReply = () => {
    axios.post(`/media/comments/${props.comment.id}/reply`, {'content': content.value}).then(res => {
        emit('reply:added', res.data.data);
        content.value = '';
    }).catch(error => {
        console.error('Error creating comment:', error);
    });
}

</script>

<style scoped>

</style>
