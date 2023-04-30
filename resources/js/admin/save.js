function formSave(){
    const btnSave = document.querySelector('#btnForSaveId')
    btnSave.setAttribute('disabled', '')
    btnSave.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}
const formSubmit = document.querySelector('#formSubmitId')
if (formSubmit){
    formSubmit.addEventListener('submit',() => formSave())
}