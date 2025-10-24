<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
  cards: Object,
});

const createForm = useForm({
  title: '',
  description: '',
  link: '',
  active: true,
  audience: 'all',
  image: null,
});

const imageFiles = reactive({});

function createCard() {
  createForm.post(route('admin.cards.store'), {
    forceFormData: true,
    onSuccess: () => createForm.reset('title', 'description', 'link', 'active', 'image'),
  });
}

function updateCard(card) {
  const form = useForm({
    title: card.title,
    description: card.description,
    link: card.link,
    active: card.active,
    audience: card.audience ?? 'all',
    image: imageFiles[card.id] || null,
  });
  form.post(route('admin.cards.update', card.id), { forceFormData: true });
}

function deleteCard(card) {
  if (confirm('Удалить карточку?')) {
    router.delete(route('admin.cards.destroy', card.id));
  }
}
</script>

<template>
  <Head title="Admin - Карточки" />
  <AdminLayout>
    <h1 class="mb-4 text-2xl font-bold text-gray-800">Карточки Dashboard</h1>

    <div class="mb-6 rounded-lg bg-white p-4 shadow">
      <h2 class="mb-3 text-lg font-semibold">Добавить карточку</h2>
      <form @submit.prevent="createCard" class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <input v-model="createForm.title" class="input" placeholder="Заголовок" required />
        <input v-model="createForm.link" class="input" placeholder="Ссылка (опционально)" />
        <textarea v-model="createForm.description" class="input sm:col-span-2" placeholder="Описание"></textarea>
        <label class="flex items-center gap-2"><input type="checkbox" v-model="createForm.active"/> Активна</label>
        <select v-model="createForm.audience" class="input">
          <option value="all">Для всех</option>
          <option value="support">Только Support</option>
          <option value="technical">Только Technical</option>
        </select>
        <input type="file" @change="e => (createForm.image = e.target.files[0])" accept="image/*" required />
        <div class="sm:col-span-2">
          <button class="rounded bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-500">Создать</button>
        </div>
        <div class="sm:col-span-2 text-sm text-red-600" v-if="Object.keys(createForm.errors).length">
          <div v-for="(msg, key) in createForm.errors" :key="key">{{ msg }}</div>
        </div>
      </form>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <div v-for="card in cards.data" :key="card.id" class="rounded-lg bg-white p-4 shadow">
        <div class="flex items-start justify-between">
          <h3 class="text-lg font-semibold">{{ card.title }}</h3>
          <button @click="deleteCard(card)" class="text-sm text-red-600 hover:underline">Удалить</button>
        </div>
        <img v-if="card.image_url" :src="card.image_url" class="mt-2 h-40 w-full rounded object-cover" />
        <div class="mt-2 text-sm text-gray-600">{{ card.description }}</div>
        <div class="mt-2 text-sm"><a v-if="card.link" :href="card.link" target="_blank" class="text-blue-600 hover:underline">Ссылка</a></div>

        <details class="mt-3">
          <summary class="cursor-pointer text-sm text-gray-700">Редактировать</summary>
          <form @submit.prevent="updateCard(card)" class="mt-2 grid grid-cols-1 gap-2">
            <input v-model="card.title" class="input" />
            <input v-model="card.link" class="input" />
            <textarea v-model="card.description" class="input"></textarea>
            <label class="flex items-center gap-2"><input type="checkbox" v-model="card.active"/> Активна</label>
            <select v-model="card.audience" class="input">
              <option value="all">Для всех</option>
              <option value="support">Только Support</option>
              <option value="technical">Только Technical</option>
            </select>
            <input type="file" accept="image/*" @change="e => (imageFiles[card.id] = e.target.files[0])" />
            <div>
              <button class="rounded bg-emerald-600 px-3 py-1 text-white hover:bg-emerald-500">Сохранить</button>
            </div>
          </form>
        </details>
      </div>
    </div>

    <div class="mt-6">
      <div class="text-sm text-gray-600">Страница {{ cards.current_page }} из {{ cards.last_page }}</div>
    </div>
  </AdminLayout>
</template>

<style scoped>
.input { @apply w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500; }
</style>
