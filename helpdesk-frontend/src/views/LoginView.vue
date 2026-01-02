<template>
  <div class="flex items-center justify-center min-h-screen bg-dark-bg">
    <div class="w-full max-w-md p-8 space-y-6 bg-card-bg rounded-lg shadow-xl border border-slate-700">
      <h1 class="text-3xl font-bold text-center text-white">Helpdesk Login</h1>

      <div v-if="errorMessage" class="p-3 text-sm text-red-200 bg-red-900 rounded">
        {{ errorMessage }}
      </div>

      <form class="space-y-4" @submit.prevent="handleLogin">
        <div>
          <label class="block text-sm font-medium text-slate-300">Email</label>
          <input 
            v-model="email"
            type="email" 
            class="w-full px-4 py-2 mt-1 text-white bg-input-bg border border-slate-600 rounded focus:outline-none focus:ring-2 focus:ring-accent-blue"
            placeholder="admin@helpdesk.com"
            required
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-300">Password</label>
          <input 
            v-model="password"
            type="password" 
            class="w-full px-4 py-2 mt-1 text-white bg-input-bg border border-slate-600 rounded focus:outline-none focus:ring-2 focus:ring-accent-blue"
            placeholder="••••••••"
            required
          >
        </div>

        <button 
          type="submit"
          :disabled="isLoading"
          class="w-full px-4 py-2 font-bold text-white bg-accent-blue rounded hover:bg-blue-600 transition disabled:opacity-50 cursor-pointer"
        >
          {{ isLoading ? 'Logging in...' : 'Sign In' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useSweetAlert } from '../composables/useSweetAlert'

const router = useRouter()
const { showToast, showAlert } = useSweetAlert()

const email = ref('')
const password = ref('')
const errorMessage = ref('')
const isLoading = ref(false)

onMounted(() => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    router.push('/tickets')
  }
})

const handleLogin = async () => {
  isLoading.value = true
  
  try {
    const response = await axios.post('/login', {
      email: email.value,
      password: password.value
    }, {
      headers: { 'Accept': 'application/json' }
    })

    const token = response.data.access_token
    localStorage.setItem('auth_token', token)
    localStorage.setItem('user_info', JSON.stringify(response.data.user))

    showToast('เข้าสู่ระบบสำเร็จ!')
    router.push('/tickets') 
  } catch (error) {
    console.error(error)
    showAlert(
      'เข้าสู่ระบบไม่สำเร็จ', 
      error.response?.data?.message || 'โปรดตรวจสอบข้อมูล'
    )
  } finally {
    isLoading.value = false
  }
}
</script>