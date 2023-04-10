const myModal = document.getElementById('exampleModal')
const myInput = document.getElementById('btn-delete')

console.log(myModal)
console.log(myInput)
myInput.addEventListener('click', function () {
    console.log('click')
    myModal.style.display = "block"
    const closeBtn = document.getElementById('closeId')
    const NoBtn = document.getElementById('btn-no')
    const YesBtn = document.getElementById('btn-yes')
    closeBtn.addEventListener('click', () => {
        myModal.style.display = "none"
    })
    NoBtn.addEventListener('click', () => {
        myModal.style.display = "none"
    })
    YesBtn.addEventListener('click', () => {
        myModal.style.display = "none"
        const id = myInput.closest('tr').id
        const router = new XMLHttpRequest()
        const body = `id=${id}`
        router.open('post', `/admin/users/delete`)
        router.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        router.onload = () => {
            if (router.response) {
                location.reload()
            }
        }
        router.send(body)
    })
})