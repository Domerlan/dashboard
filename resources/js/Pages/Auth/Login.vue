<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const propsDef = defineProps({
  canResetPassword: { type: Boolean },
  status: { type: String },
});

const form = useForm({ email: '', password: '', remember: false });
const submit = () => { form.post(route('login'), { onFinish: () => form.reset('password') }); };

const settings = computed(() => usePage().props.settings || {});
function hexToRgba(hex, alpha = 1) {
  const m = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex || '#ffffff');
  const r = parseInt(m?.[1] || 'ff', 16);
  const g = parseInt(m?.[2] || 'ff', 16);
  const b = parseInt(m?.[3] || 'ff', 16);
  return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}
const cardStyle = computed(() => ({ backgroundColor: hexToRgba(settings.value.card_bg_color || '#ffffff', Number(settings.value.card_bg_opacity ?? 0.9)) }));
</script>

<template>
  <PublicLayout :bg-url="settings.dashboard_bg_url" :logo-url="settings.dashboard_logo_url" :settings="settings">
    <Head title="Вход" />
    <div class="flex min-h-[70vh] items-center justify-center">
      <div class="w-full max-w-md rounded-xl p-6 shadow" :style="cardStyle">
        <h1 class="mb-4 text-center text-2xl font-bold text-gray-800">Вход</h1>
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">{{ status }}</div>
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700" for="email">Email</label>
            <input id="email" v-model="form.email" type="email" class="w-full rounded border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" required autofocus autocomplete="username" />
            <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
          </div>
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700" for="password">Пароль</label>
            <input id="password" v-model="form.password" type="password" class="w-full rounded border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" required autocomplete="current-password" />
            <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</div>
          </div>
          <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" v-model="form.remember" /> Запомнить меня
          </label>
          <div class="mt-2 flex items-center justify-between">
            <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-indigo-600 hover:underline">Забыли пароль?</Link>
            <button class="rounded bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-500" :disabled="form.processing">Войти</button>
          </div>
        </form>
      </div>
    </div>
  </PublicLayout>
</template>
