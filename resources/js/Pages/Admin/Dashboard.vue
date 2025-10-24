<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
  stats: Object,
});
</script>

<template>
  <Head title="Admin - Dashboard" />
  <AdminLayout>
    <h1 class="mb-6 text-2xl font-bold text-gray-800">Панель администратора</h1>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <div class="rounded-lg bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Карточки</div>
        <div class="text-3xl font-semibold text-gray-800">{{ stats.cards }}</div>
      </div>
      <div class="rounded-lg bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Пользователи</div>
        <div class="text-3xl font-semibold text-gray-800">{{ stats.users }}</div>
      </div>
      <div class="rounded-lg bg-white p-4 shadow">
        <div class="text-sm text-gray-500">На одобрении</div>
        <div class="text-3xl font-semibold text-gray-800">{{ stats.pending_users }}</div>
      </div>
    </div>
  </AdminLayout>
</template>

