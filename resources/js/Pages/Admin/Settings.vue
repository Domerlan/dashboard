<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  settings: Object,
  pings: Array,
});

const form = useForm({
  dashboard_logo: null,
  dashboard_bg: null,
  topbar_bg_color: props.settings?.topbar_bg_color ?? '#111827',
  topbar_bg_opacity: props.settings?.topbar_bg_opacity ?? 0.6,
  topbar_clock_color: props.settings?.topbar_clock_color ?? '#ffffff',
  topbar_ping_color: props.settings?.topbar_ping_color ?? '#ffffff',
  bg_effect: props.settings?.bg_effect ?? 'image',
  matrix_fg_color: props.settings?.matrix_fg_color ?? '#00ff00',
  matrix_bg_color: props.settings?.matrix_bg_color ?? '#000000',
  matrix_opacity: props.settings?.matrix_opacity ?? 0.85,
  matrix_speed: props.settings?.matrix_speed ?? 1.0,
  matrix_density: props.settings?.matrix_density ?? 20,
  card_bg_color: props.settings?.card_bg_color ?? '#ffffff',
  card_bg_opacity: props.settings?.card_bg_opacity ?? 0.9,
});

function submit() {
  form.post(route('admin.settings.update'), { forceFormData: true });
}

const pingCreateForm = useForm({ label: '', host: '', sort_order: 1, active: true });

function updatePing(p) {
  const f = useForm({ label: p.label, host: p.host, sort_order: p.sort_order, active: p.active });
  f.post(route('admin.settings.pings.update', p.id));
}
function deletePing(p) {
  if (confirm('Удалить пинг?')) router.delete(route('admin.settings.pings.destroy', p.id));
}
</script>

<template>
  <Head title="Admin - Настройки" />
  <AdminLayout>
    <h1 class="mb-4 text-2xl font-bold text-gray-800">Настройки Dashboard</h1>

    <!-- Logo / Background upload -->
    <div class="rounded-lg bg-white p-4 shadow">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
          <div class="mb-2 text-sm font-medium">Логотип</div>
          <img v-if="settings?.dashboard_logo_url" :src="settings.dashboard_logo_url" class="mb-2 h-16 w-16 rounded object-cover" />
          <input type="file" accept="image/*" @change="e => (form.dashboard_logo = e.target.files[0])" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Фон (для режима "Изображение")</div>
          <img v-if="settings?.dashboard_bg_url" :src="settings.dashboard_bg_url" class="mb-2 h-24 w-full rounded object-cover" />
          <input type="file" accept="image/*" @change="e => (form.dashboard_bg = e.target.files[0])" />
        </div>
      </div>
      <div class="mt-4">
        <button @click="submit" class="rounded bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-500">Сохранить</button>
      </div>
      <div class="mt-2 text-sm text-red-600" v-if="Object.keys(form.errors).length">
        <div v-for="(msg, key) in form.errors" :key="key">{{ msg }}</div>
      </div>
    </div>

    <!-- Background effect -->
    <div class="mt-8 rounded-lg bg-white p-4 shadow">
      <h2 class="mb-3 text-lg font-semibold">Фон Dashboard</h2>
      <div class="mb-3 flex items-center gap-4">
        <label class="flex items-center gap-2"><input type="radio" value="image" v-model="form.bg_effect"> Изображение</label>
        <label class="flex items-center gap-2"><input type="radio" value="matrix" v-model="form.bg_effect"> Матрица</label>
      </div>
      <div v-if="form.bg_effect === 'matrix'" class="grid grid-cols-1 gap-4 md:grid-cols-5">
        <div>
          <div class="mb-2 text-sm font-medium">Цвет символов</div>
          <input type="color" v-model="form.matrix_fg_color" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Цвет фона</div>
          <input type="color" v-model="form.matrix_bg_color" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Прозрачность</div>
          <input type="number" step="0.05" min="0" max="1" v-model.number="form.matrix_opacity" class="input w-28" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Скорость</div>
          <input type="number" step="0.1" min="0.1" max="5" v-model.number="form.matrix_speed" class="input w-28" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Плотность</div>
          <input type="number" min="5" max="80" v-model.number="form.matrix_density" class="input w-28" />
        </div>
      </div>
      <div class="mt-4">
        <button @click="submit" class="rounded bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-500">Сохранить</button>
      </div>
    </div>

    <!-- Topbar settings -->
    <div class="mt-8 rounded-lg bg-white p-4 shadow">
      <h2 class="mb-3 text-lg font-semibold">Верхняя панель</h2>
      <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div>
          <div class="mb-2 text-sm font-medium">Цвет панели</div>
          <input type="color" v-model="form.topbar_bg_color" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Прозрачность (0..1)</div>
          <input type="number" step="0.05" min="0" max="1" v-model.number="form.topbar_bg_opacity" class="input w-28" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Цвет часов</div>
          <input type="color" v-model="form.topbar_clock_color" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Цвет пингов</div>
          <input type="color" v-model="form.topbar_ping_color" />
        </div>
      </div>
      <div class="mt-4">
        <button @click="submit" class="rounded bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-500">Сохранить</button>
      </div>
    </div>

    <!-- Pings -->
    <div class="mt-8 rounded-lg bg-white p-4 shadow">
      <h2 class="mb-3 text-lg font-semibold">Пинги (до 5)</h2>
      <form class="mb-4 grid grid-cols-1 gap-2 md:grid-cols-5" @submit.prevent="() => pingCreateForm.post(route('admin.settings.pings.store'))">
        <input v-model="pingCreateForm.label" placeholder="Метка (TTK)" class="input" />
        <input v-model="pingCreateForm.host" placeholder="Хост (yandex.ru)" class="input" />
        <input v-model.number="pingCreateForm.sort_order" type="number" min="1" class="input" placeholder="Порядок" />
        <label class="flex items-center gap-2"><input type="checkbox" v-model="pingCreateForm.active"/> Активен</label>
        <button class="rounded bg-indigo-600 px-3 py-2 text-white">Добавить</button>
      </form>
      <div class="grid grid-cols-1 gap-3">
        <div v-for="p in pings" :key="p.id" class="grid grid-cols-1 items-center gap-2 rounded border p-3 md:grid-cols-6">
          <input v-model="p.label" class="input" />
          <input v-model="p.host" class="input" />
          <input v-model.number="p.sort_order" type="number" min="1" class="input" />
          <label class="flex items-center gap-2"><input type="checkbox" v-model="p.active"/> Активен</label>
          <button class="rounded bg-emerald-600 px-3 py-2 text-white" @click.prevent="updatePing(p)">Сохранить</button>
          <button class="rounded bg-red-600 px-3 py-2 text-white" @click.prevent="deletePing(p)">Удалить</button>
        </div>
      </div>
    </div>

    <!-- Cards block settings -->
    <div class="mt-8 rounded-lg bg-white p-4 shadow">
      <h2 class="mb-3 text-lg font-semibold">Блок карточек</h2>
      <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
          <div class="mb-2 text-sm font-medium">Цвет фона карточки</div>
          <input type="color" v-model="form.card_bg_color" />
        </div>
        <div>
          <div class="mb-2 text-sm font-medium">Прозрачность (0..1)</div>
          <input type="number" step="0.05" min="0" max="1" v-model.number="form.card_bg_opacity" class="input w-28" />
        </div>
      </div>
      <div class="mt-4">
        <button @click="submit" class="rounded bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-500">Сохранить</button>
      </div>
    </div>
  </AdminLayout>
</template>
