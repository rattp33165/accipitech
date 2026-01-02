import Swal from 'sweetalert2'

export function useSweetAlert() {
  
  const showToast = (title, icon = 'success') => {
    Swal.fire({
      icon: icon,
      title: title,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      background: '#1e293b',
      color: '#cbd5e1',
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
  }

  // 2. Alert แจ้งเตือนตรงกลาง (ใช้ตอน Error)
  const showAlert = (title, text, icon = 'error') => {
    return Swal.fire({
      icon: icon,
      title: title,
      text: text,
      background: '#1e293b',
      color: '#fff',
      confirmButtonColor: '#3b82f6'
    });
  }

  const confirmDelete = async (title = 'ยืนยันการลบ?', text = 'คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!') => {
    const result = await Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'ลบเลย!',
      cancelButtonText: 'ยกเลิก',
      background: '#1e293b',
      color: '#fff'
    });
    return result.isConfirmed;
  }

  return {
    showToast,
    showAlert,
    confirmDelete
  }
}