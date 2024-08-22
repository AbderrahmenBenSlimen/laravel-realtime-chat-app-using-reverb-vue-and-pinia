<script setup>
import { ref } from 'vue';
import { useMessagesStore } from '@/Stores/useMessagesStore.js';


const messagesStore = useMessagesStore();
const emit = defineEmits();
const model = ref('');
const shift = ref(false);

let typingTimout = null;

const handelTyping = () => {

    clearTimeout(typingTimout);
    emit('typing', true);
    typingTimout = setTimeout(handelTypingFinish, 3000);
}

const handelTypingFinish = () => {
    clearTimeout(typingTimout);
    emit('typing', false);
}


const handelEnter = () => {


    if (shift.value && model.value.length) {
        model.value += '\n';
        return;
    }

    if (model.value.length) {
        emit('valid', model.value);
        model.value = '';
    }

    handelTypingFinish();
}

</script>

<template>
    <textarea name="" id="body" rows="4" v-model="model"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        @keydown.shift="shift = true" @keyup="shift = false" @keydown="handelTyping"
        @keydown.enter.prevent="handelEnter">
    </textarea>

</template>