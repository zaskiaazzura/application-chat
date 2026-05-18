<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100">

        <div class="bg-white shadow p-4">
            <h1 class="text-2xl font-bold">
                Realtime Chat
            </h1>
        </div>

        <div class="flex h-[calc(100vh-80px)]">

            <div class="w-1/4 bg-white border-r p-4">
                Sidebar
            </div>

            <div class="flex-1 p-4">
                Chat Area
            </div>

        </div>

    </div>
</template>