<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
    conversation: Object,
    authUser: Object,
})

const emit = defineEmits(['sent'])

const newMessage = ref('')
const file = ref(null)
const fileInput = ref(null)
const sending = ref(false)

let typingTimeout = null

const typing = async () => {
    clearTimeout(typingTimeout)
    typingTimeout = setTimeout(async () => {
        await axios.post('/typing', {
            conversation_id: props.conversation.id
        })
    }, 300)
}

const handleFile = (e) => {
    file.value = e.target.files[0]
}

const clearFile = () => {
    file.value = null
    if (fileInput.value) fileInput.value.value = ''
}

const sendMessage = async () => {
    if (!newMessage.value.trim() && !file.value) return
    if (sending.value) return

    sending.value = true

    const formData = new FormData()
    formData.append('body', newMessage.value)
    formData.append('type', file.value ? 'image' : 'text')
    if (file.value) formData.append('file', file.value)

    try {
        const res = await axios.post(
            `/conversations/${props.conversation.id}/messages`,
            formData
        )
        emit('sent', res.data)
        newMessage.value = ''
        clearFile()
    } catch (e) {
        console.error('Gagal mengirim pesan', e)
    } finally {
        sending.value = false
    }
}

const handleKeydown = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault()
        sendMessage()
    }
}
</script>

<template>
    <div class="px-4 py-3 border-t border-gray-800 bg-gray-900">

        <!-- File preview -->
        <div v-if="file" class="flex items-center gap-2 mb-2 text-xs text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
            </svg>
            <span class="truncate max-w-xs">{{ file.name }}</span>
            <button @click="clearFile" class="text-red-400 hover:text-red-300 ml-1">✕</button>
        </div>

        <div class="flex items-center gap-2">
            <!-- Attach file -->
            <button
                @click="fileInput.click()"
                class="text-gray-500 hover:text-gray-300 transition-colors p-1"
                title="Lampirkan file"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
            </button>
            <input
                ref="fileInput"
                type="file"
                class="hidden"
                accept="image/*"
                @change="handleFile"
            />

            <!-- Text input -->
            <input
                v-model="newMessage"
                @input="typing"
                @keydown="handleKeydown"
                class="flex-1 bg-gray-800 text-gray-100 text-sm rounded-full px-4 py-2 outline-none placeholder-gray-500 focus:ring-1 focus:ring-indigo-500 transition"
                placeholder="Ketik pesan... (Enter untuk kirim)"
            />

            <!-- Send button -->
            <button
                @click="sendMessage"
                :disabled="sending || (!newMessage.trim() && !file)"
                class="w-9 h-9 rounded-full bg-indigo-500 hover:bg-indigo-600 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </div>
    </div>
</template>