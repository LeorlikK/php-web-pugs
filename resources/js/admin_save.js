function formSubmit(){
    console.log('asdklfskjfjhsdfijksdujifsdfs')
    const btnSave = document.querySelector('#btnForSaveId')
    btnSave.setAttribute('disabled', '')
    btnSave.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}
const formSave = document.querySelector('#formSubmitId')
if (formSave){
    formSave.addEventListener('submit',() => formSubmit())
}