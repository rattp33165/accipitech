import axios from 'axios'
import router from '../router'

axios.defaults.baseURL = 'http://127.0.0.1:8000/api'

axios.interceptors.response.use(
  response => {
    return response
  },
  error => {
    if (error.response && error.response.status === 401) {
      console.warn('Session หมดอายุ หรือไม่มีสิทธิ์เข้าถึง - กำลังจะกลับไปหน้า Login')
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_info')
      router.push('/')
    }
    return Promise.reject(error)
  }
)

export default axios