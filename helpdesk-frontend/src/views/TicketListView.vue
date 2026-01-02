<template>
  <MainLayout>
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-white">All Tickets</h2>
      <button 
        @click="openCreateModal"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition cursor-pointer"
      >
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        New Ticket
      </button>
    </div>

    <div v-if="isLoading" class="text-center py-10">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
      <p class="mt-4 text-slate-400">Loading tickets...</p>
    </div>

    <div v-else class="bg-card-bg rounded-xl border border-slate-700 overflow-hidden shadow-lg">
      <table class="w-full text-left border-collapse">
        <thead class="bg-slate-800/50 text-slate-400 uppercase text-xs">
          <tr>
            <th class="p-4 font-semibold">ID</th>
            <th class="p-4 font-semibold">Title</th>
            <th class="p-4 font-semibold">Priority</th>
            <th class="p-4 font-semibold">Status</th>
            <th class="p-4 font-semibold">Organization</th>
            <th class="p-4 font-semibold text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-700">
          <tr v-for="ticket in tickets" :key="ticket.id" class="hover:bg-slate-800/30 transition group">
            <td class="p-4 text-blue-400 font-mono">#{{ ticket.id }}</td>
            <td class="p-4 font-medium text-white">{{ ticket.title }}</td>
            <td class="p-4">
              <span :class="getPriorityColor(ticket.priority)" class="px-2 py-1 rounded text-xs font-bold">
                {{ ticket.priority }}
              </span>
            </td>
            <td class="p-4">
              <span class="px-2 py-1 rounded text-xs bg-slate-700 text-slate-300 border border-slate-600">
                {{ ticket.status }}
              </span>
            </td>
            <td class="p-4 text-slate-400">{{ ticket.organization?.name || '-' }}</td>
            <td class="p-4 text-right">
              <button 
                @click="goToDetail(ticket.id)"
                class="text-slate-400 hover:text-blue-400 mr-3 transition cursor-pointer" 
                title="View Details"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              </button>
              <button 
                @click="deleteTicket(ticket.id)"
                class="text-slate-400 hover:text-red-400 transition cursor-pointer" 
                title="Delete"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
              </button>
            </td>
          </tr>
          <tr v-if="tickets.length === 0">
            <td colspan="6" class="p-8 text-center text-slate-500">
              No tickets found. Click "New Ticket" to create one.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import MainLayout from '../layouts/MainLayout.vue'
import { useRouter } from 'vue-router'
import { useSweetAlert } from '../composables/useSweetAlert'

const router = useRouter()
const { confirmDelete, showToast, showAlert } = useSweetAlert()

const tickets = ref([])
const isLoading = ref(true)

onMounted(() => {
  fetchTickets()
})

const fetchTickets = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const response = await axios.get('/tickets', {
      headers: { Authorization: `Bearer ${token}` }
    })
    tickets.value = response.data.data
  } catch (error) {
    console.error(error)
    showAlert('Error', 'ไม่สามารถดึงข้อมูล Tickets ได้')
  } finally {
    isLoading.value = false
  }
}

const deleteTicket = async (id) => {
  const isConfirmed = await confirmDelete('ลบ Ticket นี้?', 'การกระทำนี้ไม่สามารถย้อนกลับได้')
  
  if (isConfirmed) {
    try {
      const token = localStorage.getItem('auth_token')
      await axios.delete(`/tickets/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      
      showToast('ลบ Ticket เรียบร้อยแล้ว')
      fetchTickets()
    } catch (error) {
      showAlert('Error', 'ไม่สามารถลบ Ticket ได้')
    }
  }
}

const getPriorityColor = (priority) => {
  switch (priority) {
    case 'High': return 'bg-red-500/20 text-red-400 border border-red-500/30'
    case 'Medium': return 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30'
    case 'Low': return 'bg-green-500/20 text-green-400 border border-green-500/30'
    default: return 'bg-slate-600 text-white'
  }
}

const goToDetail = (id) => {
  router.push(`/tickets/${id}`)
}

const openCreateModal = () => {
  router.push('/tickets/create')
}
</script>