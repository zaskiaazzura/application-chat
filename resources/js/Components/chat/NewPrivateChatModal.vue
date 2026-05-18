<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
    authUser: Object,
})

const emit = defineEmits(['close', 'created'])

const users = ref([])
const loading = ref(false)
const starting = ref(null)

const fetchUsers = async () => {
    loading.value = true
    try {
        const res = await axios.get('/users')
        users.value = res.data
    } finally {
        loading.value = false
    }
}

const startChat = async (user) => {
    starting.value = user.id
    try {
        const res = await axios.post('/conversations', {
            type: 'private',
            participants: [user.id],
        })
        emit('created', res.data)
    } finally {
        starting.value = null
    }
}

onMounted(() => fetchUsers())
</script>

<template>
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center" @click.self="emit('close')">
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-md mx-4 shadow-2xl">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
                <h2 class="text-sm font-semibold text-gray-100">Mulai Chat Baru</h2>
                <button @click="emit('close')" class="text-gray-500 hover:text-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-5 py-4">
                <p class="text-xs text-gray-500 mb-3">Pilih user untuk diajak chat</p>

                <div v-if="loading" class="text-sm text-gray-500 text-center py-6">Memuat...</div>

                <div v-else class="space-y-1 max-h-72 overflow-y-auto">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 cursor-pointer transition-colors"
                        @click="startChat(user)"
                    >
                        <div class="relative">
                            <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold text-white">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <span
                                v-if="user.is_online"
                                class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 rounded-full border-2 border-gray-900"
                            />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-200">{{ user.name }}</p>
                            <p class="text-xs" :class="user.is_online ? 'text-green-400' : 'text-gray-500'">
                                {{ user.is_online ? 'Online' : 'Offline' }}
                            </p>
                        </div>
                        <span v-if="starting === user.id" class="text-xs text-gray-500">Memulai...</span>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>

                    <div v-if="users.length === 0" class="text-sm text-gray-600 text-center py-6">
                        Tidak ada user lain.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>