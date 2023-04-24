function clickImage(event){
    const fullName = event.target.closest('.col-3.mt-4').querySelector('.name-for-img').name
    const bigImage = document.querySelector('body')
    bigImage.insertAdjacentHTML( 'afterbegin',`
    <div class="box">
        <div class="box-border">
            <div class="box-img" style="background-image: url(${event.target.src})">
                <span class="close" id="closeBigImgId"></span>
            </div>
            <p class="box-img-name">${fullName}</p>
        </div>
    </div>`)

    const btnCloseBigImage = document.querySelector('#closeBigImgId')
    if (btnCloseBigImage){
        console.log(btnCloseBigImage)
        btnCloseBigImage.addEventListener('click', (event) => funcCloseBigImage(event))
    }
}

function onDelete(target){
    target.setAttribute('disabled', '')
    target.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>')
}

function funcCloseBigImage(event){
    if (event.target.className !== 'box-img'){
        const imageBox = document.querySelector('.box')
        imageBox.remove()
    }
}

const images = document.querySelector('.container.mt-1')
images.addEventListener('click', function (event){
    if (event.target.className === 'photo-small'){
        clickImage(event)
    }
})

images.addEventListener('submit', function (event){
    console.log(event.target[1]);
    const target = event.target[1]
    if(target.className === 'btn btn-primary button-for-image'){
        onDelete(target)
    }
})
