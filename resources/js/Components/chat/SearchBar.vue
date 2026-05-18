<script setup>
import { ref } from 'vue'
import axios from 'axios'

const search = ref('')
const results = ref([])

const searchMessages = async () => {

    const response = await axios.get(
        '/api/messages/search',
        {
            params: {
                search: search.value
            }
        }
    )

    results.value = response.data
}
</script>

<template>
    <div class="p-4">

        <input
            v-model="search"
            @input="searchMessages"
            placeholder="Cari pesan..."
            class="border p-2 w-full rounded"
        />

        <div
            v-for="message in results"
            :key="message.id"
        >
            {{ message.body }}
        </div>

    </div>
</template>