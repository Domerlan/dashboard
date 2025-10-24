<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  card: Object,
  settings: Object,
});

function hexToRgba(hex, alpha = 1) {
  const m = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex || '#ffffff');
  const r = parseInt(m?.[1] || 'ff', 16);
  const g = parseInt(m?.[2] || 'ff', 16);
  const b = parseInt(m?.[3] || 'ff', 16);
  return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}
const cardBg = computed(() => hexToRgba(props.settings?.card_bg_color || '#ffffff', Number(props.settings?.card_bg_opacity ?? 0.9)));
</script>

<template>
  <PublicLayout :bg-url="settings?.dashboard_bg_url" :logo-url="settings?.dashboard_logo_url" :settings="settings">
    <Head :title="card.title" />
    <div class="mx-auto max-w-3xl p-4">
      <Link :href="route('dashboard')" class="inline-block rounded bg-white/20 px-3 py-1 text-sm text-white hover:bg-white/30">На главную</Link>
      <div class="mt-4 overflow-hidden rounded-xl shadow" :style="{ backgroundColor: cardBg }">
        <img v-if="card.image_url" :src="card.image_url" :alt="card.title" class="h-72 w-full object-cover" />
        <div class="p-6">
          <h1 class="mb-2 text-2xl font-bold text-gray-800">{{ card.title }}</h1>
          <p class="whitespace-pre-line text-gray-700">{{ card.description }}</p>
          <div v-if="card.link" class="mt-4">
            <a :href="card.link" target="_blank" rel="noopener" class="rounded bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-500">Перейти по ссылке</a>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>
