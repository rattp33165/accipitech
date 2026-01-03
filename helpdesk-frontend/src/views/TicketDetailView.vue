<template>
  <MainLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold text-white">Ticket Details</h2>
      <div class="flex gap-3">
        <button 
          @click="router.back()"
          class="px-4 py-2 text-slate-400 hover:text-white transition cursor-pointer"
        >
          Back
        </button>
        <button 
          @click="updateTicket"
          :disabled="isSaving"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg shadow-blue-500/20 transition cursor-pointer disabled:opacity-50"
        >
          {{ isSaving ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </div>

    <div v-if="isLoading" class="text-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-[4fr_3fr_3fr] gap-6">
      
      <div class="bg-card-bg border border-slate-700 rounded-xl flex flex-col sm:flex-row h-full shadow-lg overflow-hidden relative">
        
        <div class="flex-1 p-6 relative border-b sm:border-b-0 sm:border-r border-dashed border-slate-600">
          
          <div class="flex items-center justify-between mb-4">
              <h3 class="text-xl font-bold text-white flex items-center gap-2">
                Ticket
              </h3>
          </div>

          <div class="space-y-3 text-sm">
            <div class="mb-4 relative group">
              <input 
                v-model="form.title" 
                class="w-full bg-transparent text-slate-200 border-b border-transparent hover:border-slate-600 focus:border-blue-500 focus:outline-none py-1 transition placeholder-slate-600 pr-8"
                placeholder="Ticket Title"
              />
              <div class="absolute right-0 top-1/2 -translate-y-1/2 text-slate-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </div>
            </div>

            <div class="flex">
              <span class="w-24 text-slate-500">Ticket Id:</span>
              <span class="text-yellow-500 font-mono font-bold">#{{ form.id }}</span>
            </div>
            
            <div class="flex">
              <span class="w-24 text-slate-500">Created:</span>
              <span class="text-slate-300">{{ form.created_at }}</span>
            </div>
            
            <div class="flex items-center group">
              <span class="w-24 text-slate-500">Contact: <span class="text-red-500">*</span></span>
              <div class="flex-1 flex items-center">
                <input 
                  v-model="form.contact_email" 
                  class="w-full bg-transparent text-slate-200 border-b border-transparent hover:border-slate-600 focus:border-blue-500 focus:outline-none py-1 transition placeholder-slate-600 pr-8"
                />
                <div class="relative group">
                  <div class="absolute right-0 top-1/2 -translate-y-1/2 text-slate-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="flex items-start mt-2">
              <span class="w-24 text-slate-500 pt-1">Cc Email:</span>
              <div class="flex-1">
                <div v-for="(email, index) in ccList" :key="index" class="flex items-center gap-2 mb-1 group">
                  <span class="text-slate-300 text-sm">{{ email }}</span>
                  <button 
                    @click="removeCc(index)"
                    class="text-xs text-slate-600 hover:text-red-400 cursor-pointer opacity-0 group-hover:opacity-100 transition"
                  >
                    (remove)
                  </button>
                </div>
                
                <div class="mt-2">
                  <button 
                    v-if="!showCcInput" 
                    @click="showCcInput = true" 
                    class="bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 rounded-full px-2 py-0.5 text-xs flex items-center gap-1 transition"
                  >
                    <span class="text-lg leading-none">+</span>
                  </button>
                  <div v-else class="flex items-center gap-2">
                    <input 
                      v-model="ccInput"
                      @keyup.enter="addCc"
                      @blur="showCcInput = false"
                      type="email" 
                      placeholder="Add email..." 
                      class="bg-slate-800 rounded px-2 py-1 text-slate-300 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 border border-slate-700 w-full"
                      autoFocus
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-8 pt-4 border-t border-slate-700/50">
            <textarea 
              v-model="form.description" 
              rows="4"
              class="w-full bg-transparent text-slate-400 text-sm border-none focus:outline-none resize-none placeholder-slate-600 leading-relaxed"
              placeholder="This is a description of the ticket. It should just be a few lines and describe the issue."
            ></textarea>
          </div>
        </div>

        <div class="w-full sm:w-48 bg-card-bg p-6 flex flex-col items-center justify-center text-center relative border-l-0 sm:border-l border-slate-700/50">
            
            <div class="hidden sm:block absolute -left-3 top-0 w-6 h-6 bg-dark-bg rounded-full z-10"></div>
            <div class="hidden sm:block absolute -left-3 bottom-0 w-6 h-6 bg-dark-bg rounded-full z-10"></div>
            
            <div class="absolute top-6 right-6 text-yellow-500">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
            </div>

            <div class="flex-1 flex flex-col justify-center w-full">
              <div class="mb-8">
                <div class="mb-1">
                  <span 
                    class="font-bold text-2xl tracking-widest uppercase"
                    :class="{
                      'text-red-500': form.priority === 'High',
                      'text-yellow-500': form.priority === 'Medium',
                      'text-green-500': form.priority === 'Low'
                    }"
                  >
                    {{ form.priority }}
                  </span>
                </div>
                <p class="text-slate-500 text-[10px] uppercase tracking-wider">ticket priority</p>
              </div>

              <div class="w-full">
                <span class="text-white font-bold text-lg block">{{ timeRemaining }}</span>
                <p class="text-slate-500 text-[10px] uppercase tracking-wider">time remaining</p>
              </div>
            </div>
        </div>
      </div>

      <div class="bg-card-bg border border-slate-700 rounded-xl p-6 h-full shadow-lg relative">
        <div class="absolute top-6 right-6 text-yellow-500">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="5" r="3"></circle>
              <line x1="12" y1="22" x2="12" y2="8"></line>
              <path d="M5 12H2a10 10 0 0 0 20 0h-3"></path>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-white mb-6">Organization</h3>

        <div class="space-y-6">
          <div class="form-group">
            <label class="text-xs text-slate-400 mb-1 block">Organization <span class="text-red-500">*</span></label>
            <select v-model="form.organization_id" @change="fetchVessels" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
              <option v-for="org in lookups.organizations" :key="org.id" :value="org.id">{{ org.name }}</option>
            </select>
          </div>

          <div class="form-group">
            <label class="text-xs text-slate-400 mb-1 block">Vessel <span class="text-red-500">*</span></label>
            <select v-model="form.vessel_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
              <option v-for="vessel in lookups.vessels" :key="vessel.id" :value="vessel.id">{{ vessel.name }}</option>
            </select>
          </div>

            <div class="form-group">
            <label class="text-xs text-slate-400 mb-1 block">Assigned To <span class="text-red-500">*</span></label>
            <select v-model="form.assigned_to_user_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
              <option :value="null">Currently Unassigned</option>
              <option v-for="user in lookups.users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
          </div>
        </div>
      </div>

      <div class="bg-card-bg border border-slate-700 rounded-xl p-6 h-full shadow-lg relative">
        <div class="absolute top-6 right-6 text-yellow-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-6">Category</h3>

        <div class="space-y-6">
          <div class="form-group">
            <label class="text-xs text-slate-400 mb-1 block">Category <span class="text-red-500">*</span></label>
            <select v-model="form.category_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
              <option v-for="cat in lookups.categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>

          <div class="form-group">
            <label class="text-xs text-slate-400 mb-1 block">Status <span class="text-red-500">*</span></label>
            <select v-model="form.status" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
              <option value="Open">Open</option>
              <option value="In Progress">In Progress</option>
              <option value="Resolved">Resolved</option>
              <option value="Closed">Closed</option>
            </select>
          </div>

          <div class="form-group">
            <label class="text-xs text-slate-400 mb-1 block">Service Line <span class="text-red-500">*</span></label>
            <select v-model="form.service_line_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
              <option v-for="sl in lookups.serviceLines" :key="sl.id" :value="sl.id">{{ sl.name }}</option>
            </select>
          </div>
        </div>
      </div>

    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import MainLayout from '../layouts/MainLayout.vue'
import { useSweetAlert } from '../composables/useSweetAlert'

const route = useRoute()
const router = useRouter()
const { showToast, showAlert } = useSweetAlert()

const isLoading = ref(true)
const isSaving = ref(false)

// Variables
const lookups = reactive({
  organizations: [],
  vessels: [],
  categories: [],
  serviceLines: [],
  users: []
})

const form = ref({
  id: '',
  title: '',
  description: '',
  contact_email: '',
  priority: '',
  status: '',
  organization_id: null,
  vessel_id: null,
  category_id: null,
  service_line_id: null,
  assigned_to_user_id: null,
  created_at: ''
})

const ccInput = ref('')
const ccList = ref([])
const showCcInput = ref(false) // toggle input box

// Lifecycle
onMounted(async () => {
  await fetchLookups()
  await fetchTicketDetail()
})

// Validation
const validateForm = () => {
  if (!form.value.title) return 'กรุณาระบุ Title'
  if (!form.value.contact_email) return 'กรุณาระบุ Contact Email'
  if (!form.value.organization_id) return 'กรุณาเลือก Organization'
  if (!form.value.vessel_id) return 'กรุณาเลือก Vessel'
  if (!form.value.assigned_to_user_id) return 'กรุณาเลือก Assigned To'
  if (!form.value.priority) return 'กรุณาเลือก Priority'
  if (!form.value.category_id) return 'กรุณาเลือก Category'
  if (!form.value.service_line_id) return 'กรุณาเลือก Service Line'
  return null 
}

// Data Fetching
const fetchLookups = async () => {
  const token = localStorage.getItem('auth_token')
  const headers = { Authorization: `Bearer ${token}` }
  
  try {
    const [orgs, cats, sls, usrs] = await Promise.all([
      axios.get('/lookups/organizations', { headers }),
      axios.get('/lookups/categories', { headers }),
      axios.get('/lookups/service-lines', { headers }),
      axios.get('/lookups/users', { headers })
    ])

    lookups.organizations = orgs.data
    lookups.categories = cats.data
    lookups.serviceLines = sls.data
    lookups.users = usrs.data
    
  } catch (error) {
    console.error("Lookup Error:", error)
  }
}

const fetchTicketDetail = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const response = await axios.get(`/tickets/${route.params.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    
    const data = response.data.data
    form.value = {
        ...data,
        organization_id: data.organization.id,
        vessel_id: data.vessel.id,
        category_id: data.category.id,
        service_line_id: data.service_line.id,
        assigned_to_user_id: data.assigned_to?.id || null
    }

    if (data.cc_emails) {
      ccList.value = data.cc_emails.split(',').map(e => e.trim()) 
    } else {
      ccList.value = []
    }

    await fetchVessels()

  } catch (error) {
    showAlert('Error', 'ไม่พบข้อมูล Ticket')
    router.push('/tickets')
  } finally {
    isLoading.value = false
  }
}

const fetchVessels = async () => {
  if (!form.value.organization_id) return
  
  const token = localStorage.getItem('auth_token')
  const response = await axios.get(`/lookups/vessels?organization_id=${form.value.organization_id}`, {
      headers: { Authorization: `Bearer ${token}` }
  })
  lookups.vessels = response.data
}

// Actions
const updateTicket = async () => {
  const error = validateForm()
  if (error) {
    showAlert('ข้อมูลไม่ครบถ้วน', error)
    return
  }

  isSaving.value = true
  form.value.cc_emails = ccList.value.join(',')
  try {
    const token = localStorage.getItem('auth_token')
    await axios.put(`/tickets/${route.params.id}`, form.value, {
       headers: { Authorization: `Bearer ${token}` }
    })
    
    showToast('บันทึกข้อมูลเรียบร้อย')
  } catch (error) {
    showAlert('Error', 'ไม่สามารถบันทึกข้อมูลได้')
  } finally {
    isSaving.value = false
  }
}

const addCc = () => {
  if (ccInput.value && ccInput.value.includes('@')) {
    ccList.value.push(ccInput.value.trim())
    ccInput.value = ''
    showCcInput.value = false
  } else {
    showAlert('Email ไม่ถูกต้อง', 'กรุณากรอกรูปแบบ Email ให้ถูกต้อง')
  }
}

const removeCc = (index) => {
  ccList.value.splice(index, 1)
}

// Computed
const timeRemaining = computed(() => {
  if (!form.value.created_at) return 'Calculating...'
  
  const createdDate = new Date(form.value.created_at)
  const deadline = new Date(createdDate.getTime() + (48 * 60 * 60 * 1000)) // SLA 48 Hours
  const now = new Date()
  
  const diff = deadline - now
  
  if (diff <= 0) return 'Overdue'
  
  const hours = Math.floor(diff / (1000 * 60 * 60))
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))
  
  return `${hours}h ${minutes}min`
})
</script>