<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
    authUser: Object,
})

const emit = defineEmits(['close', 'created'])

const groupName = ref('')
const users = ref([])
const selectedIds = ref([])
const loading = ref(false)
const submitting = ref(false)

const fetchUsers = async () => {
    loading.value = true
    try {
        const res = await axios.get('/users')
        users.value = res.data
    } finally {
        loading.value = false
    }
}

const toggleUser = (id) => {
    if (selectedIds.value.includes(id)) {
        selectedIds.value = selectedIds.value.filter(i => i !== id)
    } else {
        selectedIds.value.push(id)
    }
}

const submit = async () => {
    if (!groupName.value.trim() || selectedIds.value.length === 0) return
    submitting.value = true
    try {
        const res = await axios.post('/conversations', {
            type: 'group',
            name: groupName.value,
            participants: selectedIds.value,
        })
        emit('created', res.data)
    } finally {
        submitting.value = false
    }
}

onMounted(() => fetchUsers())
</script>

<template>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center" @click.self="emit('close')">
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-md mx-4 shadow-2xl">

            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
                <h2 class="text-sm font-semibold text-gray-100">Buat Grup Baru</h2>
                <button @click="emit('close')" class="text-gray-500 hover:text-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-5 py-4 space-y-4">
                <!-- Group name -->
                <input
                    v-model="groupName"
                    class="w-full bg-gray-800 text-gray-100 text-sm rounded-lg px-4 py-2.5 outline-none placeholder-gray-500 focus:ring-1 focus:ring-indigo-500"
                    placeholder="Nama grup..."
                />

                <!-- User list -->
                <div>
                    <p class="text-xs text-gray-500 mb-2">Pilih anggota</p>

                    <div v-if="loading" class="text-sm text-gray-500 text-center py-4">Memuat...</div>

                    <div v-else class="space-y-1 max-h-60 overflow-y-auto">
                        <div
                            v-for="user in users"
                            :key="user.id"
                            @click="toggleUser(user.id)"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-colors"
                            :class="selectedIds.includes(user.id) ? 'bg-indigo-600/30 border border-indigo-500/50' : 'hover:bg-gray-800'"
                        >
                            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <span class="text-sm text-gray-200 flex-1">{{ user.name }}</span>
                            <svg v-if="selectedIds.includes(user.id)" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <div v-if="users.length === 0" class="text-sm text-gray-600 text-center py-4">
                            Tidak ada user lain.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-5 py-4 border-t border-gray-700 flex justify-end gap-2">
                <button
                    @click="emit('close')"
                    class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 transition-colors"
                >
                    Batal
                </button>
                <button
                    @click="submit"
                    :disabled="submitting || !groupName.trim() || selectedIds.length === 0"
                    class="px-4 py-2 text-sm bg-indigo-600 hover:bg-indigo-500 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-lg transition-colors"
                >
                    {{ submitting ? 'Membuat...' : 'Buat Grup' }}
                </button>
            </div>
        </div>
    </div>
</template>