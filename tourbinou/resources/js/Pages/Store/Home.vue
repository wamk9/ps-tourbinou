<script setup>
import { Head } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import Api from '@/Helpers/Api/Api.js'
</script>

<template>
    <Head title="Loja" />

    <main id="store-page">
        <div id="container">
            <p v-if="items.length == 0 && isCompleted" class="text-lg text-center">
                Sem items para exibir, que tal cadastrar algo no <a :href="route('pages.admin.login')">painel administrativo</a>?
            </p>
            <Card
                v-for="item in items" :key="item.id"
                :name="item.name"
                :description="item.description"
                :destination="item.destination"
                :hour="item.hour"
            />
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            isCompleted: false,
        };
    },
    async created() {
        const result = await Api.getAsync(route('api.store.index'));

        if (result.code == 200) {
            this.items = result.response.message;
        }

        this.isCompleted = true;
    }
}
</script>
