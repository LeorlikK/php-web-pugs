const oldAndNewValues = {
}

function change(event){
    const btnTarget = event.target
    if (btnTarget.textContent === ' Изменить'){
        const allInput = document.querySelectorAll('.change')
        allInput.forEach(function (item){
            if ('selectedIndex' in item){
                console.log('DADADA')
                oldAndNewValues[item.name] = item.selectedIndex
            }else{
                oldAndNewValues[item.name] = item.value
            }
            item.removeAttribute('disabled')
        })
        console.log(oldAndNewValues)
        document.querySelector('#btnForLoginSaveId').removeAttribute('disabled')
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
        document.querySelector('#btnForLoginSaveId').setAttribute('disabled', '')
        btnTarget.textContent = ' Изменить'

    }
}
const btnChange = document.querySelector('#btnForLoginChangeId')
if (btnChange){
    btnChange.addEventListener('click', (event) => change(event))
}
const btnSave = document.querySelector('#btnForLoginSaveId')

