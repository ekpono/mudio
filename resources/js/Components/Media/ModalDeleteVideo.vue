<template>
    <Modal :show="toggleModal" @close="emit('close:modal')" :maxWidth="'sm'">
        <div class="m-5">
            <div class="mb-4">
                Delete Midnight Love
            </div>
            <hr>
            <div class="mt-4">
                Are you sure you want to delete this file?
            </div>
            <div class="mt-5 space-x-3 text-right">
                <PrimaryButton @click="deleteMedia">Delete</PrimaryButton>
                <SecondaryButton @click="emit('close:modal')"
                >Cancel</SecondaryButton
                >
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
const emit = defineEmits(['close:modal', 'delete:success'])
let props = defineProps(['toggleModal', 'media'])

const deleteMedia = () => {
    axios.delete(`/file/${props.media.id}`).then(res => {
        emit('delete:success')
    })
}
</script>
