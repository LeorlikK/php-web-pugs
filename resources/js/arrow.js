const upBtn = document.querySelector('#arrow-down-id')
const downBtn = document.querySelector('#arrow-up-id')

upBtn.addEventListener('click',  (event) => {
    document.querySelector('#selectorArrowId input').value = 'DESC'
    event.target.className = 'arrow-btn up-active'
    downBtn.className = 'arrow-btn down'
})
downBtn.addEventListener('click',  (event) => {
    document.querySelector('#selectorArrowId input').value = 'ASC'
    event.target.className = 'arrow-btn down-active'
    upBtn.className = 'arrow-btn up'
})


