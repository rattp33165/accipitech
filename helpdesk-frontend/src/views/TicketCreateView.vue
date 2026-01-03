<template>
  <MainLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold text-white">Create New Ticket</h2>
      <div class="flex gap-3">
        <button 
          @click="router.back()"
          class="px-4 py-2 text-slate-400 hover:text-white transition cursor-pointer"
        >
          Cancel
        </button>
        <button 
          @click="createTicket"
          :disabled="isSaving"
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg shadow-green-500/20 transition cursor-pointer disabled:opacity-50"
        >
          {{ isSaving ? 'Creating...' : 'Create Ticket' }}
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-card-bg border border-slate-700 rounded-xl p-6 relative shadow-lg">
          <h3 class="text-xl font-bold text-white mb-4">Ticket Info</h3>
          
          <div class="space-y-4">
            <div>
              <label class="text-xs text-slate-400 mb-1 block">Title <span class="text-red-500">*</span></label>
              <input 
                v-model="form.title" 
                placeholder="Enter ticket title..."
                class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500"
              />
            </div>

            <div class="flex items-center gap-4">
               <div class="flex-1">
                 <label class="text-xs text-slate-400 mb-1 block">Contact Email <span class="text-red-500">*</span></label>
                 <input v-model="form.contact_email" type="email" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500">
               </div>
            </div>

            <div>
              <label class="text-xs text-slate-400 mb-1 block">Cc Emails</label>
              <div class="flex flex-wrap gap-2 mb-2" v-if="ccList.length > 0">
                <span v-for="(email, index) in ccList" :key="index" class="bg-slate-700 text-xs px-2 py-1 rounded-full flex items-center gap-1 text-slate-300">
                  {{ email }}
                  <button @click="removeCc(index)" class="hover:text-red-400 font-bold ml-1">×</button>
                </span>
              </div>
              <div class="flex gap-2">
                <input 
                  v-model="ccInput"
                  @keyup.enter="addCc"
                  type="email" 
                  placeholder="Add email and press Enter..." 
                  class="flex-1 bg-input-bg border border-slate-600 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-blue-500"
                >
                <button @click="addCc" class="bg-slate-700 hover:bg-slate-600 text-white px-3 rounded-lg">+</button>
              </div>
            </div>
          </div>

          <hr class="border-slate-700 my-6">

          <div>
             <label class="text-xs text-slate-400 mb-1 block">Description</label>
             <textarea 
               v-model="form.description" 
               rows="5"
               class="w-full bg-input-bg border border-slate-600 rounded-lg p-3 text-white focus:outline-none focus:border-blue-500 resize-none"
               placeholder="Describe the issue..."
             ></textarea>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-card-bg border border-slate-700 rounded-xl p-6 h-full shadow-lg">
          <h3 class="text-xl font-bold text-white mb-6">Organization</h3>

          <div class="space-y-6">
            <div class="form-group">
              <label class="text-xs text-slate-400 mb-1 block">Organization <span class="text-red-500">*</span></label>
              <select v-model="form.organization_id" @change="fetchVessels" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
                <option :value="null" disabled>Select Organization</option>
                <option v-for="org in lookups.organizations" :key="org.id" :value="org.id">{{ org.name }}</option>
              </select>
            </div>

            <div class="form-group">
              <label class="text-xs text-slate-400 mb-1 block">Vessel <span class="text-red-500">*</span></label>
              <select v-model="form.vessel_id" :disabled="!form.organization_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none disabled:opacity-50">
                <option :value="null">Select Vessel</option>
                <option v-for="vessel in lookups.vessels" :key="vessel.id" :value="vessel.id">{{ vessel.name }}</option>
              </select>
            </div>

             <div class="form-group">
              <label class="text-xs text-slate-400 mb-1 block">Assigned To <span class="text-red-500">*</span></label>
              <select v-model="form.assigned_to_user_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
                <option :value="null">-- Unassigned --</option>
                <option v-for="user in lookups.users" :key="user.id" :value="user.id">{{ user.name }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-card-bg border border-slate-700 rounded-xl p-6 h-full shadow-lg">
          <h3 class="text-xl font-bold text-white mb-6">Details</h3>

          <div class="space-y-6">
            <div class="form-group">
              <label class="text-xs text-slate-400 mb-1 block">Priority <span class="text-red-500">*</span></label>
              <select v-model="form.priority" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
              </select>
            </div>

            <div class="form-group">
              <label class="text-xs text-slate-400 mb-1 block">Category <span class="text-red-500">*</span></label>
              <select v-model="form.category_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
                <option :value="null" disabled>Select Category</option>
                <option v-for="cat in lookups.categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
            </div>

            <div class="form-group">
              <label class="text-xs text-slate-400 mb-1 block">Service Line <span class="text-red-500">*</span></label>
              <select v-model="form.service_line_id" class="w-full bg-input-bg border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 appearance-none">
                <option :value="null" disabled>Select Service Line</option>
                <option v-for="sl in lookups.serviceLines" :key="sl.id" :value="sl.id">{{ sl.name }}</option>
              </select>
            </div>
            
            <div class="form-group">
               <label class="text-xs text-slate-400 mb-1 block">Initial Status</label>
               <input value="Open" disabled class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-slate-500">
            </div>

          </div>
        </div>
      </div>

    </div>
  </MainLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import MainLayout from '../layouts/MainLayout.vue'
import { useSweetAlert } from '../composables/useSweetAlert'

const router = useRouter()
const { showToast, showAlert } = useSweetAlert()

const isSaving = ref(false)
const ccInput = ref('')
const ccList = ref([])

const form = ref({
  title: '',
  description: '',
  contact_email: '',
  priority: 'Medium',
  status: 'Open',
  organization_id: null,
  vessel_id: null,
  category_id: null,
  service_line_id: null,
  assigned_to_user_id: null,
  cc_emails: ''
})

const lookups = reactive({
  organizations: [],
  vessels: [],
  categories: [],
  serviceLines: [],
  users: []
})

onMounted(() => {
  fetchLookups()
})

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
    console.error(error)
  }
}

const fetchVessels = async () => {
  if (!form.value.organization_id) {
      lookups.vessels = []
      form.value.vessel_id = null
      return
  }
  const token = localStorage.getItem('auth_token')
  const response = await axios.get(`/lookups/vessels?organization_id=${form.value.organization_id}`, {
      headers: { Authorization: `Bearer ${token}` }
  })
  lookups.vessels = response.data
  form.value.vessel_id = null
}

const addCc = () => {
  if (ccInput.value && ccInput.value.includes('@')) {
    ccList.value.push(ccInput.value.trim())
    ccInput.value = ''
  } else {
    showAlert('Error', 'Invalid Email format')
  }
}

const removeCc = (index) => {
  ccList.value.splice(index, 1)
}

const createTicket = async () => {
  const error = validateForm()
  if (error) {
    showAlert('ข้อมูลไม่ครบถ้วน', error)
    return
  }

  isSaving.value = true
  form.value.cc_emails = ccList.value.join(',')

  try {
    const token = localStorage.getItem('auth_token')
    await axios.post('/tickets', form.value, {
       headers: { Authorization: `Bearer ${token}` }
    })
    showToast('สร้าง Ticket สำเร็จ!', 'success')

  } catch (error) {
    console.error(error)
    if (error.response && error.response.status === 422) {
      const messages = Object.values(error.response.data.errors).flat().join('\n')
      showAlert('ข้อผิดพลาดในการตรวจสอบข้อมูล', messages)
      return
    }
    showAlert('Error', error.response?.data?.message || 'ไม่สามารถสร้าง Ticket ได้')
  } finally {
    isSaving.value = false
  }
}
</script>