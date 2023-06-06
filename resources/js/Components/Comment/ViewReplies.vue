<template>
    <div class="mt-2">
        <div v-for="(review, reviewIdx) in replies" :key="review.id" class="flex space-x-4 text-sm text-gray-500">
            <div class="flex-none py-5">
                <img :src="review.avatarSrc" alt="" class="h-10 w-10 rounded-full" />
            </div>
            <div :class="[reviewIdx === 0 ? '' : 'border-t border-gray-200', 'flex-1 py-5 mb-5']">
                <h3 class="font-medium text-gray-900">{{ review.author }} <time :datetime="review.datetime" class="text-gray-500">{{ review.date }}</time></h3>
                <div class="prose prose-sm mt-2 max-w-none text-gray-500" v-html="review.content" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, toRef } from "vue";
const props = defineProps(['replies']);
const replies = toRef(props, 'replies');
</script>

<style scoped>

</style>
