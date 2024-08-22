<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatTexterea from '@/Components/Chat/ChatTexterea.vue';
import ChatMessages from '@/Components/Chat/ChatMessages.vue';
import ChatUsers from '@/Components/Chat/ChatUsers.vue';
import { Head } from '@inertiajs/vue3';
import { useMessagesStore } from '@/Stores/useMessagesStore.js';
import { useUsersStore } from '@/Stores/useUsersStore.js';
import { onUnmounted } from 'vue';


const messagesStore = useMessagesStore();
const usersStore = useUsersStore();
const props = defineProps({
    room: Object,
})
messagesStore.fetchState(props.room.slug);

const storeMessage = (payload) => {
    messagesStore.storeMessage(props.room.slug, payload);
}

onUnmounted(() => {
    Echo.leave(`room.${props.room.id}`);
})

const channel = Echo.join(`room.${props.room.id}`);

channel
    .listen('MessageCreated', (e) => {

        messagesStore.pushMessage(e);
    })
    .here(users => usersStore.setUsers(users))
    .joining(user => usersStore.addUser(user))
    .leaving(user => usersStore.removeUser(user))
    .listenForWhisper('typing', e => usersStore.setTyping(e)
    );
;
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ room.title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-12 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-3">
                    <div class="p-6 text-gray-900">
                        <ChatUsers />
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-9 space-y-3">
                    <ChatMessages :room="room" />
                    <div class="p-6 text-gray-900">
                        <ChatTexterea class="w-full" placeholder="Write somthing..."
                            @valid="storeMessage({ body: $event })"
                            @typing="channel.whisper('typing', { id: $page.props.auth.user.id, typing: $event })" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
