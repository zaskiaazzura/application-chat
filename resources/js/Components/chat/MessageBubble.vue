<script setup>
import { computed } from 'vue'

const props = defineProps({
    message: Object,
    isMine: Boolean,
})

const time = computed(() => {
    if (!props.message.created_at) return ''
    return new Date(props.message.created_at).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
    })
})
</script>

<template>
    <div
        class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl text-sm"
        :class="isMine
            ? 'bg-indigo-600 text-white rounded-br-sm'
            : 'bg-gray-800 text-gray-100 rounded-bl-sm'"
    >
        <!-- Reply quote -->
        <div
            v-if="message.replyTo"
            class="border-l-2 border-indigo-300 pl-2 mb-2 text-xs opacity-70"
        >
            <p class="font-medium">{{ message.replyTo.sender?.name }}</p>
            <p class="truncate">{{ message.replyTo.body }}</p>
        </div>

        <!-- Text body -->
        <p v-if="message.body" class="leading-relaxed whitespace-pre-wrap break-words">{{ message.body }}</p>

        <!-- Image attachment -->
        <img
            v-if="message.attachment"
            :src="`/storage/${message.attachment}`"
            class="mt-2 rounded-lg max-w-full cursor-pointer"
            @click="window.open(`/storage/${message.attachment}`, '_blank')"
        />

        <!-- Timestamp -->
        <p class="text-right mt-1 text-xs opacity-50">{{ time }}</p>
    </div>
</template>