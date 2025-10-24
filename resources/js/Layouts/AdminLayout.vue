<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const roles = computed(() => usePage().props.auth?.roles ?? []);
const isAdmin = computed(() => roles.value.includes('admin'));
const isMager = computed(() => roles.value.includes('mager'));

const menu = computed(() => {
  const items = [];
  if (isAdmin.value || isMager.value) items.push({ label: 'Dashboard', routeName: 'admin.index' });
  if (isAdmin.value || isMager.value) items.push({ label: 'Карточки', routeName: 'admin.cards.index' });
  if (isAdmin.value) items.push({ label: 'Пользователи', routeName: 'admin.users.index' });
  if (isAdmin.value) items.push({ label: 'Настройки', routeName: 'admin.settings.index' });
  return items;
});
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <div class="mx-auto max-w-7xl p-4 pr-72">
      <slot />
    </div>

    <aside class="fixed right-0 top-0 z-40 h-full w-64 bg-gray-900/60 backdrop-blur-lg">
      <div class="flex h-full flex-col p-4 text-white">
        <h2 class="mb-4 text-xl font-semibold">Admin</h2>
        <nav class="flex flex-1 flex-col gap-2">
          <Link v-for="(item, idx) in menu" :key="idx" :href="route(item.routeName)" class="rounded px-3 py-2 hover:bg-gray-800/50">
            {{ item.label }}
          </Link>
        </nav>
        <div class="mt-4 border-t border-white/20 pt-4">
          <Link :href="route('logout')" method="post" as="button" class="w-full rounded bg-red-500/80 px-3 py-2 text-left font-medium text-white hover:bg-red-500">Выход</Link>
        </div>
      </div>
    </aside>
  </div>
</template>
