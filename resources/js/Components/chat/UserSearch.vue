<script setup>
import { ref } from 'vue'
import axios from 'axios'

const searchText = ref('')
const users = ref([])

const searchUsers = async () => {

    if (!searchText.value) {
        users.value = []
        return
    }

    const response = await axios.get(
        '/api/users/search',
        {
            params: {
                search: searchText.value
            }
        }
    )

    users.value = response.data
}
</script>

<template>
    <div class="p-4">

        <input
            v-model="searchText"
            @input="searchUsers"
            placeholder="Cari user..."
            class="border p-2 w-full rounded"
        >

        <div
            v-for="user in users"
            :key="user.id"
            class="p-2 border-b"
        >
            {{ user.name }}
        </div>

    </div>
</template>