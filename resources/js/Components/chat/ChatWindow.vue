<script setup>
import { ref, watch, nextTick, onUnmounted } from 'vue'
import axios from 'axios'
import MessageBubble from '@/Components/chat/MessageBubble.vue'
import TypingIndicator from '@/Components/chat/TypingIndicator.vue'

const props = defineProps({
    conversation: Object,
    authUser: Object,
})

const messages = ref([])
const typingUser = ref(null)
const container = ref(null)
let echoChannel = null

const scrollToBottom = async () => {
    await nextTick()
    if (container.value) {
        container.value.scrollTop = container.value.scrollHeight
    }
}

const fetchMessages = async () => {
    messages.value = []
    const res = await axios.get(`/conversations/${props.conversation.id}/messages`)
    // Handle paginated response
    const data = res.data?.data ?? res.data
    messages.value = Array.isArray(data) ? [...data].reverse() : data
    scrollToBottom()
}

const subscribeEcho = () => {
    if (echoChannel) {
        window.Echo.leave(`conversation.${props.conversation.id}`)
    }

    echoChannel = window.Echo
        .private(`conversation.${props.conversation.id}`)
        .listen('.message.sent', (e) => {
            messages.value.push(e.message)
            scrollToBottom()
        })
        .listen('.user.typing', () => {
            typingUser.value = 'Sedang mengetik...'
            setTimeout(() => { typingUser.value = null }, 2000)
        })
}

watch(() => props.conversation?.id, (newId) => {
    if (newId) {
        fetchMessages()
        subscribeEcho()
    }
}, { immediate: true })

onUnmounted(() => {
    if (props.conversation) {
        window.Echo.leave(`conversation.${props.conversation.id}`)
    }
})
</script>

<template>
    <div
        ref="container"
        class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-2 bg-gray-950"
    >
        <div v-if="messages.length === 0" class="flex-1 flex items-center justify-center">
            <p class="text-sm text-gray-600">Belum ada pesan. Mulai percakapan!</p>
        </div>

        <div
            v-for="message in messages"
            :key="message.id"
            class="flex"
            :class="message.sender_id === authUser.id ? 'justify-end' : 'justify-start'"
        >
            <MessageBubble
                :message="message"
                :is-mine="message.sender_id === authUser.id"
            />
        </div>

        <TypingIndicator v-if="typingUser" :name="typingUser" />
    </div>
</template>