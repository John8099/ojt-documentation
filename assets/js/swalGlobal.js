window.swalAlert = function ($title = 'TEST', $msg = 'text', $icon = 'success', callback = () => { }) {
  swal.fire({
    title: $title,
    html: $msg,
    icon: $icon,
  }).then(() => callback())
}

window.showLoading = function () {
  Swal.fire({
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      swal.showLoading()
    },
  })
}

window.closeAlert = function () {
  swal.close()
}

window.swalConfirm = function (text = "", confirmCallback = () => { }, cancelCallback = () => { }) {
  swal.fire({
    // title: 'Select Adviser',
    icon: 'question',
    html: text,
    showDenyButton: true,
    confirmButtonText: 'Yes',
    denyButtonText: 'No',
    allowOutsideClick: false,
    allowEscapeKey: false,
  }).then((res) => {
    if (res.isConfirmed) {
      confirmCallback()
    }
    else {
      cancelCallback()
    }
  })

}