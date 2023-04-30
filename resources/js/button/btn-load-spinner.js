function onLoad(){
    const btn = document.querySelector('#btnFormId')
    btn.setAttribute('disabled', '')
    btn.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}

const btnLoading = document.querySelector('#formSubmitId')
if (btnLoading){
    btnLoading.addEventListener('submit', () => onLoad())
}