<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onBeforeUnmount, ref, computed } from 'vue';

const props = defineProps({
  cards: { type: Array, default: () => [] },
  settings: { type: Object, default: null },
});

const bgUrl = props?.settings?.dashboard_bg_url ?? null;
const logoUrl = props?.settings?.dashboard_logo_url ?? null;

const clock = ref('');
const pings = ref([]);
let intervalId = null;

function updateClock() {
  const d = new Date();
  const opts = { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
  clock.value = d.toLocaleString('ru-RU', opts);
}

async function fetchPings() {
  try {
    const res = await fetch('/api/pings', { cache: 'no-store' });
    if (res.ok) {
      pings.value = await res.json();
    }
  } catch (e) {}
}

onMounted(() => {
  updateClock();
  fetchPings();
  intervalId = setInterval(() => {
    updateClock();
    fetchPings();
  }, 5000);
});

onBeforeUnmount(() => {
  if (intervalId) clearInterval(intervalId);
});

function buildLink(url) {
  if (!url) return '#';
  try {
    const u = new URL(url, window.location.origin);
    if (!/^https?:$/.test(u.protocol)) {
      u.protocol = 'http:';
    }
    return u.href;
  } catch (_) {
    return `http://${url}`;
  }
}

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
  <Head title="Dashboard" />

  <PublicLayout :bg-url="bgUrl" :logo-url="logoUrl" :settings="props.settings" :pings="pings" :clock="clock">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div
        v-for="card in cards"
        :key="card.id"
        class="group overflow-hidden rounded-xl shadow transition hover:shadow-lg"
        :style="{ backgroundColor: cardBg }"
      >
        <Link :href="`/cards/${card.id}`">
          <img
            v-if="card.image_url"
            :src="card.image_url"
            :alt="card.title"
            class="h-40 w-full object-cover transition group-hover:scale-105"
            loading="lazy"
            decoding="async"
          />
          <div class="p-4">
            <h3 class="truncate text-lg font-semibold text-gray-800">{{ card.title }}</h3>
            <p class="mt-2 line-clamp-3 text-sm text-gray-700">{{ card.description }}</p>
          </div>
        </Link>
      </div>
    </div>
  </PublicLayout>
</template>
