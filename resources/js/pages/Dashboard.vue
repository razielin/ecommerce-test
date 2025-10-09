<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Orders from '@/pages/Orders.vue';

</script>

<template>
    <AppLayout>
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Orders />
            </div>
        </div>
    </AppLayout>
</template>
