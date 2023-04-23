const upBtn = document.querySelector('#arrow-down-id')
const downBtn = document.querySelector('#arrow-up-id')

const sorted = new URLSearchParams(window.location.search);
const upOrDown = sorted.get('sorted');
if (upOrDown === 'down'){
    upBtn.className = 'arrow-btn up-active'
}else{
    downBtn.className = 'arrow-btn down-active'
}

upBtn.addEventListener('click',  (event) => {
    document.querySelector('#selectorArrowId input').value = 'down'
    event.target.className = 'arrow-btn up-active'
    downBtn.className = 'arrow-btn down'
})
downBtn.addEventListener('click',  (event) => {
    document.querySelector('#selectorArrowId input').value = 'up'
    event.target.className = 'arrow-btn down-active'
    upBtn.className = 'arrow-btn up'
})


