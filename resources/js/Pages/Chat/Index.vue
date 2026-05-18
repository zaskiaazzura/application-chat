<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted} from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

import Sidebar from '@/Components/chat/Sidebar.vue'
import ChatHeader from '@/Components/chat/ChatHeader.vue'
import ChatWindow from '@/Components/chat/ChatWindow.vue'
import ChatInput from '@/Components/chat/ChatInput.vue'
import CreateGroupModal from '@/Components/chat/CreateGroupModal.vue'
import NewPrivateChatModal from '@/Components/chat/NewPrivateChatModal.vue'

const page = usePage()
const authUser = ref(page.props.auth?.user ?? { name: '', id: null })

const conversations = ref([])
const selectedConversation = ref(null)
const showCreateGroup = ref(false)
const showPrivateChat = ref(false)
const showNewMenu = ref(false)

const fetchConversations = async () => {
    const res = await axios.get('/conversations')
    conversations.value = res.data
}

const selectConversation = (conversation) => {
    selectedConversation.value = conversation
}

const onMessageSent = (message) => {
    if (!selectedConversation.value) return
    const conv = conversations.value.find(c => c.id === selectedConversation.value.id)
    if (conv) conv.last_message = message.body
}

const onConversationCreated = (conversation) => {
    const exists = conversations.value.find(c => c.id === conversation.id)
    if (!exists) conversations.value.unshift(conversation)
    selectedConversation.value = conversation
    showCreateGroup.value = false
    showPrivateChat.value = false
    showNewMenu.value = false
}

onMounted(() => {
    fetchConversations()
})
</script>

<template>
    <div class="h-screen flex bg-gray-950 text-gray-100 overflow-hidden font-sans">

        <!-- Sidebar -->
        <div class="w-80 flex-shrink-0 flex flex-col border-r border-gray-800 bg-gray-900">

            <!-- App Header -->
            <div class="flex items-center justify-between px-4 py-4 border-b border-gray-800">
                <!-- Profile info -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold">
                        {{ authUser.name ? authUser.name.charAt(0).toUpperCase() : '?' }}
                    </div>
                    <span class="font-semibold text-sm text-gray-200">{{ authUser.name }}</span>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center gap-1 relative">
                    <!-- New chat button -->
                    <button
                        @click="showNewMenu = !showNewMenu"
                        class="text-gray-400 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-gray-700"
                        title="Chat Baru"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>

                    <!-- Logout button -->
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-gray-400 hover:text-red-400 transition-colors p-1.5 rounded-lg hover:bg-gray-700"
                        title="Logout"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </Link>

                    <!-- Dropdown menu -->
                    <div
                        v-if="showNewMenu"
                        class="absolute right-0 top-10 w-44 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-10 overflow-hidden"
                    >
                        <button
                            @click="showPrivateChat = true; showNewMenu = false"
                            class="w-full text-left px-4 py-3 text-sm text-gray-200 hover:bg-gray-700 flex items-center gap-2 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Chat Personal
                        </button>
                        <button
                            @click="showCreateGroup = true; showNewMenu = false"
                            class="w-full text-left px-4 py-3 text-sm text-gray-200 hover:bg-gray-700 flex items-center gap-2 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Buat Grup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Conversation List -->
            <div class="flex-1 overflow-y-auto">
                <Sidebar
                    :conversations="conversations"
                    :selected-id="selectedConversation?.id"
                    :auth-user="authUser"
                    @select="selectConversation"
                />
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col">
            <template v-if="selectedConversation">
                <ChatHeader :conversation="selectedConversation" :auth-user="authUser" />
                <ChatWindow :conversation="selectedConversation" :auth-user="authUser" />
                <ChatInput :conversation="selectedConversation" :auth-user="authUser" @sent="onMessageSent" />
            </template>

            <template v-else>
                <div class="flex-1 flex flex-col items-center justify-center text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-lg font-medium text-gray-500">Pilih percakapan</p>
                    <p class="text-sm text-gray-600 mt-1">Klik + untuk mulai chat baru</p>
                </div>
            </template>
        </div>

        <!-- Modals -->
        <NewPrivateChatModal
            v-if="showPrivateChat"
            :auth-user="authUser"
            @close="showPrivateChat = false"
            @created="onConversationCreated"
        />

        <CreateGroupModal
            v-if="showCreateGroup"
            :auth-user="authUser"
            @close="showCreateGroup = false"
            @created="onConversationCreated"
        />
    </div>
</template>