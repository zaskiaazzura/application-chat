<script setup>
defineProps({ name: String })
</script>

<template>
    <div class="flex justify-start">
        <div class="bg-gray-800 text-gray-400 text-xs px-4 py-2 rounded-2xl flex items-center gap-2">
            <span>{{ name }} sedang mengetik</span>
            <span class="flex gap-0.5">
                <span class="w-1 h-1 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms" />
                <span class="w-1 h-1 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms" />
                <span class="w-1 h-1 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms" />
            </span>
        </div>
    </div>
</template>