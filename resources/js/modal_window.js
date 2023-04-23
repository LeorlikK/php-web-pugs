import {request} from "/resources/js/request.js";

function yes(){
    myModal.style.display = "none"
    const match = deleteId.match(/\d+$/);
    const id = match ? parseInt(match[0]) : null;
    const body = `id=${id}`
    const route = window.location.pathname + '/delete'
    const headers = {
        name: 'Content-Type',
        value: 'application/x-www-form-urlencoded'
    }
    request(route, 'post', body, headers)
        .then(data => location.reload())
        .catch(error => console.log(error))
}

function close(){
    myModal.style.display = "none"
}

let deleteId = null
const myModal = document.getElementById('exampleModal')
const myInput = document.querySelector('.table.admin-table')

const closeBtn = document.getElementById('closeId')
const NoBtn = document.getElementById('btn-no')
const YesBtn = document.getElementById('btn-yes')

closeBtn.addEventListener('click', () => close())
NoBtn.addEventListener('click', () => close())
YesBtn.addEventListener('click', (event) => yes(event))

myInput.addEventListener('click', function (event) {
    if (event.target.className === 'admin-btn-delete'){
        myModal.style.display = "block"
        deleteId = event.target.id
    }
})