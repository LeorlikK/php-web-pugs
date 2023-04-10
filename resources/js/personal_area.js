const olnAndNewLogin = {
    old: null,
    new: null
}
function onLoad(event){
    event.target.setAttribute('disabled', '')
    event.target.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}
function formLoad(event){
    console.log('asdkjasdgasukdhjasdasd')
    const btn = event.target.querySelector('#btnForLoginSaveId')
    btn.setAttribute('disabled', '')
    btn.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}
function formLoadAvatar(event){
    const btn = event.target.querySelector('#btnPhotosId')
    btn.setAttribute('disabled', '')
    btn.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}
function changeBlocked(event){
    const btnTarget = event.target
    const inputFiled = btnTarget.previousElementSibling
    if (btnTarget.textContent === ' Изменить'){
        olnAndNewLogin.old = inputFiled.value
        btnTarget.textContent = ' Отмена'
        inputFiled.removeAttribute('disabled')
    }else if (btnTarget.textContent === ' Отмена'){
        btnTarget.textContent = ' Изменить'
        inputFiled.value = olnAndNewLogin.old
        inputFiled.setAttribute('disabled', '')
    }
}

function funcLoginArea(event, btnForLoginSaveId){
    if (event.target.value !== olnAndNewLogin.old){
        btnForLoginSaveId.removeAttribute('disabled')
    }else{
        btnForLoginSaveId.setAttribute('disabled', '')
    }
    console.log(btnForLoginSaveId)
}

function funcPhotoArea(event, photoArea){
    console.log(photoArea.files)
    console.log(event)
    if (photoArea.files.length > 0){
        document.querySelector('#btnPhotosId').removeAttribute('disabled')
    }else{
        document.querySelector('#btnPhotosId').setAttribute('disabled', '')
    }
}

function funcRelationsId(event){
    if (event.target.value !== ''){
        document.querySelector('#btnRelationsId').removeAttribute('disabled')
    }else{
        document.querySelector('#btnRelationsId').setAttribute('disabled', '')
    }
}

const btnForLoginChangeId = document.querySelector('#btnForLoginChangeId')
if (btnForLoginChangeId){
    btnForLoginChangeId.addEventListener('click', (event) => changeBlocked(event))
}

const btnForLoginSaveId = document.querySelector('#btnForLoginSaveId')
if (btnForLoginSaveId){
    btnForLoginSaveId.addEventListener('submit', (event) => onLoad(event))
}

const btnFormId = document.querySelector('#FormChangeLoginId')
if (btnFormId){
    btnFormId.addEventListener('submit', (event) => formLoad(event))
}

const loginArea = document.querySelector('#login-area')
if (loginArea){
    loginArea.addEventListener('input', (event) => funcLoginArea(event, btnForLoginSaveId))
}

const photoArea = document.querySelector('#examplePhotos')
if (photoArea){
    photoArea.addEventListener('change', (event) => funcPhotoArea(event, photoArea))
}

const textareaRelationsId = document.querySelector('#textAreaId')
if (textareaRelationsId){
    textareaRelationsId.addEventListener('input', (event) => funcRelationsId(event))
}

const btnChangeAvatar = document.querySelector('#formSubmitId')
if (btnChangeAvatar){
    btnChangeAvatar.addEventListener('submit', (event) => formLoadAvatar(event))
}


