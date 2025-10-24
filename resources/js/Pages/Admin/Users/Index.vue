<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  users: Object,
  roles: Array,
});

function toggleApprove(user) {
  const routeName = user.is_approved ? 'admin.users.revoke' : 'admin.users.approve';
  router.post(route(routeName, user.id));
}

function setRoles(user, roles) {
  const form = useForm({ roles });
  form.post(route('admin.users.roles', user.id));
}
</script>

<template>
  <Head title="Admin - Пользователи" />
  <AdminLayout>
    <h1 class="mb-4 text-2xl font-bold text-gray-800">Пользователи</h1>
    <div class="overflow-hidden rounded-lg bg-white shadow">
      <table class="w-full table-fixed">
        <thead class="bg-gray-50 text-left text-sm text-gray-600">
          <tr>
            <th class="p-3">ID</th>
            <th class="p-3">Имя</th>
            <th class="p-3">Email</th>
            <th class="p-3">Роли</th>
            <th class="p-3">Одобрен</th>
            <th class="p-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users.data" :key="u.id" class="border-t text-sm">
            <td class="p-3">{{ u.id }}</td>
            <td class="p-3">{{ u.name }}</td>
            <td class="p-3">{{ u.email }}</td>
            <td class="p-3">
              <div class="flex flex-wrap gap-2">
                <label v-for="r in roles" :key="r" class="flex items-center gap-1">
                  <input type="checkbox" :checked="u.roles.includes(r)" @change="(e) => {
                    const newRoles = new Set(u.roles);
                    e.target.checked ? newRoles.add(r) : newRoles.delete(r);
                    setRoles(u, Array.from(newRoles));
                  }" />
                  <span class="capitalize">{{ r }}</span>
                </label>
              </div>
            </td>
            <td class="p-3">
              <span :class="u.is_approved ? 'text-emerald-600' : 'text-gray-500'">{{ u.is_approved ? 'Да' : 'Нет' }}</span>
            </td>
            <td class="p-3 text-right">
              <button @click="toggleApprove(u)" class="rounded bg-indigo-600 px-3 py-1 text-white hover:bg-indigo-500">
                {{ u.is_approved ? 'Снять' : 'Одобрить' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>

