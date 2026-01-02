<template>
  <div class="flex h-screen bg-dark-bg text-slate-300 font-sans overflow-hidden">
    
    <aside class="w-16 flex flex-col items-center py-6 border-r border-slate-700 bg-dark-bg z-20">
      <div class="mb-8 p-2 bg-yellow-600/20 rounded-lg text-yellow-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
      </div>

      <nav class="flex-1 space-y-6">
        <a href="#" class="block p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition">
           <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
        </a>
        <a href="#" class="block p-2 text-blue-400 bg-blue-500/10 rounded-lg transition relative">
           <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r-full"></span>
           <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        </a>
        </nav>

      <div class="mt-auto">
        <button @click="handleLogout" class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition cursor-pointer" title="Logout">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
        </button>
      </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden relative">
      <header class="h-16 flex items-center justify-between px-8 border-b border-slate-800 bg-dark-bg z-10">
        <h1 class="text-xl font-bold text-white">Helpdesk</h1>
        <div class="flex items-center space-x-4">
           <div class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center text-xs font-bold text-white">
             {{ userInfo.name.charAt(0) }}
           </div>
        </div>
      </header>

      <div class="flex-1 overflow-auto p-8 relative">
        <slot></slot>
      </div>
    </main>

  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useSweetAlert } from '../composables/useSweetAlert'

const router = useRouter()
const { confirmDelete, showToast } = useSweetAlert()

const userInfo = JSON.parse(localStorage.getItem('user_info') || '{}')

const handleLogout = async () => {
  const confirmed = await confirmDelete('ออกจากระบบ?', 'คุณต้องการ Logout ใช่หรือไม่')
  if (confirmed) {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user_info')
    showToast('Logout เรียบร้อย', 'success')
    router.push('/')
  }
}
</script>