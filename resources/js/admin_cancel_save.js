const oldAndNewValues = {
}

function change(event){
    const btnTarget = event.target
    if (btnTarget.textContent === ' Изменить'){
        const allInput = document.querySelectorAll('.change')
        allInput.forEach(function (item){
            if ('selectedIndex' in item){
                oldAndNewValues[item.name] = item.selectedIndex
            }else{
                oldAndNewValues[item.name] = item.value
            }
            item.removeAttribute('disabled')
        })
        document.querySelector('#btnForAllSaveId').removeAttribute('disabled')
        btnTarget.textContent = ' Отмена'
    }else if (btnTarget.textContent === ' Отмена'){
        const allInput = document.querySelectorAll('.change')
        allInput.forEach(function (item){
            if (item.name === 'role'){
                const selected = document.querySelector('select.change')
                selected.selectedIndex = item
            }else{
                item.value = oldAndNewValues[item.name]
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
const btnChange = document.querySelector('#btnForLoginChangeId')
if (btnChange){
    btnChange.addEventListener('click', (event) => change(event))
}
const btnSave = document.querySelector('#formAllSaveId')
if (btnSave){
    btnSave.addEventListener('submit', (event) => formLoad(event, btnChange))
}
