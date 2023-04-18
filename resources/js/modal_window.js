let deleteId = null
const myModal = document.getElementById('exampleModal')
const myInput = document.querySelector('.table.admin-table')

const closeBtn = document.getElementById('closeId')
const NoBtn = document.getElementById('btn-no')
const YesBtn = document.getElementById('btn-yes')
console.log(closeBtn)
console.log(NoBtn)
console.log(YesBtn)

closeBtn.addEventListener('click', () => close())

NoBtn.addEventListener('click', () => close())

YesBtn.addEventListener('click', (event) => yes(event))

function yes(event){
    myModal.style.display = "none"
    const match = deleteId.match(/\d+$/);
    const id = match ? parseInt(match[0]) : null;
    const router = new XMLHttpRequest()
    const body = `id=${id}`
    const route = window.location.pathname + '/delete'
    console.log(body, route)
    router.open('post', route)
    router.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    router.onload = () => {
        if (router.response) {
            console.log(router.response)
            location.reload()
        }
    }
    router.send(body)
}

function close(){
    myModal.style.display = "none"
}
myInput.addEventListener('click', function (event) {
    if (event.target.className === 'admin-btn-delete'){
        myModal.style.display = "block"
        deleteId = event.target.id
        console.log('Yes')
        console.log(deleteId)
    }
    console.log('No')
})