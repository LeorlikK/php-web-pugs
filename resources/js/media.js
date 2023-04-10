function clickImage(element){
    const bigImage = document.querySelector('.big-image')
    bigImage.innerHTML = `<img src="${element.src}" alt="..." class="img-thumbnail photo-full">`
}

function onDelete(target){
    target.setAttribute('disabled', '')
    target.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}

function onLoad(){
    const btn = document.querySelector('#btnFormId')
    btn.setAttribute('disabled', '')
    btn.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}

const images = document.querySelector('.container.mt-1')
images.addEventListener('click', function (event){
    if (event.target.className === 'img-thumbnail photo-small'){
        clickImage(event.target)
    }
})
images.addEventListener('submit', function (event){
    console.log(event.target[1]);
    const target = event.target[1]
    if(target.className === 'btn btn-primary button-for-image'){
        onDelete(target)
    }
})


const btnLoading = document.querySelector('#formSubmitId')
if (btnLoading){
    btnLoading.addEventListener('submit', () => onLoad())
}