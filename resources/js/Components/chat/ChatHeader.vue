<script setup>
import { computed } from 'vue'

const props = defineProps({
    conversation: Object,
    authUser: Object,
})

const otherUser = computed(() => {
    if (props.conversation.type === 'group') return null
    return props.conversation.participants?.find(p => p.id !== props.authUser.id)
})

const title = computed(() => {
    if (props.conversation.type === 'group') return props.conversation.name
    return otherUser.value?.name ?? 'Unknown'
})

const subtitle = computed(() => {
    if (props.conversation.type === 'group') {
        const count = props.conversation.participants?.length ?? 0
        return `${count} anggota`
    }
    return otherUser.value?.is_online ? 'Online' : 'Offline'
})

const isOnline = computed(() => {
    if (props.conversation.type === 'group') return false
    return otherUser.value?.is_online ?? false
})

const initial = computed(() => title.value.charAt(0).toUpperCase())
</script>

<template>
    <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-800 bg-gray-900">
        <!-- Avatar -->
        <div class="relative">
            <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold text-white">
                {{ initial }}
            </div>
            <span
                v-if="isOnline"
                class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 rounded-full border-2 border-gray-900"
            />
        </div>

        <!-- Info -->
        <div>
            <p class="text-sm font-semibold text-gray-100">{{ title }}</p>
            <p class="text-xs" :class="isOnline ? 'text-green-400' : 'text-gray-500'">
                {{ subtitle }}
            </p>
        </div>
    </div>
</template>