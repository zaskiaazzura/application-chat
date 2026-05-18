<script setup>
import { computed } from 'vue'

const props = defineProps({
    conversations: Array,
    selectedId: Number,
    authUser: Object,
})

const emit = defineEmits(['select'])

const getConversationName = (conv) => {
    if (conv.type === 'group') return conv.name
    const other = conv.participants?.find(p => p.id !== props.authUser.id)
    return other?.name ?? 'Unknown'
}

const getAvatar = (conv) => {
    if (conv.type === 'group') return null
    const other = conv.participants?.find(p => p.id !== props.authUser.id)
    return other?.avatar ?? null
}

const getInitial = (conv) => {
    return getConversationName(conv).charAt(0).toUpperCase()
}

const isOnline = (conv) => {
    if (conv.type === 'group') return false
    const other = conv.participants?.find(p => p.id !== props.authUser.id)
    return other?.is_online ?? false
}

const avatarColors = [
    'bg-indigo-500', 'bg-pink-500', 'bg-emerald-500',
    'bg-amber-500', 'bg-cyan-500', 'bg-violet-500'
]

const getColor = (conv) => {
    const idx = (conv.id ?? 0) % avatarColors.length
    return avatarColors[idx]
}
</script>

<template>
    <div v-if="conversations.length === 0" class="p-6 text-center text-gray-600 text-sm">
        Belum ada percakapan.<br>Mulai chat atau buat grup baru.
    </div>

    <div
        v-for="conv in conversations"
        :key="conv.id"
        @click="emit('select', conv)"
        class="flex items-center gap-3 px-4 py-3 cursor-pointer transition-colors hover:bg-gray-800"
        :class="selectedId === conv.id ? 'bg-gray-800 border-l-2 border-indigo-500' : 'border-l-2 border-transparent'"
    >
        <!-- Avatar -->
        <div class="relative flex-shrink-0">
            <img
                v-if="getAvatar(conv)"
                :src="getAvatar(conv)"
                class="w-10 h-10 rounded-full object-cover"
            />
            <div
                v-else
                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white"
                :class="getColor(conv)"
            >
                {{ getInitial(conv) }}
            </div>

            <!-- Online badge -->
            <span
                v-if="isOnline(conv)"
                class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 rounded-full border-2 border-gray-900"
            />
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-200 truncate">
                    {{ getConversationName(conv) }}
                </span>
                <span
                    v-if="conv.unread_count > 0"
                    class="ml-2 flex-shrink-0 bg-indigo-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full"
                >
                    {{ conv.unread_count }}
                </span>
            </div>
            <p class="text-xs text-gray-500 truncate mt-0.5">
                {{ conv.last_message ?? 'Belum ada pesan' }}
            </p>
        </div>
    </div>
</template>