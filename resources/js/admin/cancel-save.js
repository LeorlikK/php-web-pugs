let defaultValues = {
}

function disabledOff(){
    const allChangeInput = document.querySelectorAll('.change')
    allChangeInput.forEach(function (item){
        if ('selectedIndex' in item){
            defaultValues[item.name] = item.selectedIndex
        }else{
            defaultValues[item.name] = item.value
        }
        item.removeAttribute('disabled')
    })
}
function change(event){
    const btnTarget = event.target
    if (btnTarget.textContent === ' Изменить'){
        disabledOff()
        document.cookie = `old_value=true`
        setLocalStorage(defaultValues)
        document.querySelector('#btnForAllSaveId').removeAttribute('disabled')
        btnTarget.textContent = ' Отмена'
    }else if (btnTarget.textContent === ' Отмена'){
        const allChangeInput = document.querySelectorAll('.change')
        allChangeInput.forEach(function (item){
            if (item.name === 'role'){
                const selected = document.querySelector('select.change')
                selected.selectedIndex = defaultValues.role
            }else{
                item.value = defaultValues[item.name]
                item.textContent = item.value
            }
            item.setAttribute('disabled', '')
        })
        document.querySelector('#btnForAllSaveId').setAttribute('disabled', '')
        btnTarget.textContent = ' Изменить'
    }
}
function formLoad(event, btnChange){
    const btn = event.target.querySelector('#btnForAllSaveId')
    btn.setAttribute('disabled', '')
    btn.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
    btnChange.remove()
}
function setLocalStorage(){
    localStorage.setItem('old', JSON.stringify(defaultValues))
}
function getLocalStorage(){
    let myCookie = localStorage.getItem('old')
    return JSON.parse(myCookie)
}
const btnChange = document.querySelector('#btnForLoginChangeId')
if (btnChange){
    btnChange.addEventListener('click', (event) => change(event))
}
const formSave = document.querySelector('#formAllSaveId')
if (formSave){
    formSave.addEventListener('submit', (event) => formLoad(event, btnChange))
}
const btnSave = document.querySelector('#btnForAllSaveId')

if (document.cookie.indexOf('old_value') !== -1){
    disabledOff()
    defaultValues = getLocalStorage()
    btnChange.innerHTML = ' Отмена'
    btnSave.removeAttribute('disabled')
}else{
    localStorage.removeItem('old')
}
