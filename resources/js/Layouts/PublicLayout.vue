<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import MatrixRain from '@/Components/MatrixRain.vue';

const props = defineProps({
  bgUrl: { type: String, default: null },
  logoUrl: { type: String, default: null },
  settings: { type: Object, default: null },
  pings: { type: Array, default: () => [] },
  clock: { type: String, default: '' },
});

const user = computed(() => usePage().props.auth?.user ?? null);
const roles = computed(() => usePage().props.auth?.roles ?? []);
const isAdmin = computed(() => roles.value?.includes('admin'));
const isMager = computed(() => roles.value?.includes('mager'));

function hexToRgba(hex, alpha = 1) {
  const res = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex || '#111827');
  const r = parseInt(res?.[1] || '11', 16);
  const g = parseInt(res?.[2] || '18', 16);
  const b = parseInt(res?.[3] || '27', 16);
  return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

const barStyle = computed(() => ({
  backgroundColor: hexToRgba(props.settings?.topbar_bg_color, Number(props.settings?.topbar_bg_opacity ?? 0.6)),
}));

const clockColor = computed(() => ({ color: props.settings?.topbar_clock_color || '#fff' }));
const pingColor = computed(() => ({ color: props.settings?.topbar_ping_color || '#fff' }));
</script>

<template>
  <div
    class="relative min-h-screen w-full"
  >
    <!-- Background layer: image or matrix (behind content) -->
    <div class="fixed inset-0 z-0">
      <template v-if="settings?.bg_effect === 'matrix'">
        <MatrixRain :fg="settings?.matrix_fg_color || '#00ff00'" :bg="settings?.matrix_bg_color || '#000000'" :opacity="Number(settings?.matrix_opacity ?? 0.85)" :speed="Number(settings?.matrix_speed ?? 1)" :density="Number(settings?.matrix_density ?? 20)" />
      </template>
      <template v-else-if="user">
        <div class="h-full w-full" :style="bgUrl ? { backgroundImage: `url(${bgUrl})`, backgroundSize: 'cover', backgroundPosition: 'center' } : {}"></div>
      </template>
    </div>
    <!-- Top overlay bar (above content) -->
    <div class="fixed left-0 top-0 z-30 w-full">
      <div class="w-full" :style="barStyle">
        <div class="flex w-full items-center px-4 py-2 text-sm">
          <!-- Left: clock strictly at left edge -->
          <div class="min-w-[200px] flex-none" :style="clockColor">{{ clock }}</div>

          <!-- Center: pings, distribute evenly across available space -->
          <div class="mx-3 flex-1">
            <div class="flex flex-wrap justify-evenly gap-x-6 gap-y-1" :style="pingColor">
              <template v-for="(p, i) in pings" :key="i">
                <span>{{ p.label }} {{ p.host }} <b v-if="p.ms != null">{{ p.ms }}ms</b><span v-else>вЂ”</span></span>
              </template>
            </div>
          </div>

          <!-- Right: single action button -->
          <div class="flex flex-none items-center gap-2">
            <template v-if="user && (isAdmin || isMager)">
              <Link :href="route('admin.index')" class="rounded bg-white/20 px-3 py-1 text-white hover:bg-white/30">РђРґРјРёРЅРёСЃС‚СЂРёСЂРѕРІР°РЅРёРµ</Link>
            </template>
            <template v-else-if="user">
              <Link :href="route('logout')" method="post" as="button" class="rounded bg-red-500/80 px-3 py-1 text-white hover:bg-red-500">Р’С‹С…РѕРґ</Link>
            </template>
          </div>
        </div>
      </div>
    </div>
    <div class="relative z-10">
      <header class="mx-auto flex max-w-7xl items-center justify-between p-4 pt-16">
        <div class="flex items-center gap-3">
          <img v-if="logoUrl" :src="logoUrl" alt="Logo" class="h-12 w-12 rounded-md shadow" />
          <h1 class="text-lg font-semibold text-gray-800">Courier Plus</h1>
        </div>
        <slot name="actions" />
      </header>
      <main class="mx-auto max-w-7xl p-4">
        <slot />
      </main>
    </div>
  </div>
  
</template>


