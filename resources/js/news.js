import {request} from "/resources/js/request.js";

function funcLoadFirstsComments(event, lastElement, comments){
    const target = event.target
    const targetParent = event.target.parentNode
    let targetTextContent = event.target.textContent

    if (targetTextContent.indexOf('Показать ответы') !== -1){
        target.textContent = 'Скрыть комментарии'

        comments.forEach(function (item){
            loadComments(lastElement, item)
        })

        if (target.value <= targetParent.querySelector('.btn-add-comments').value){
            targetParent.querySelector('.btn-add-comments').setAttribute('hidden', 'hidden')
        }else{
            targetParent.querySelector('.btn-add-comments').removeAttribute('hidden')
        }
    }
}

function funcLoadLastComments(event, lastElement, comments){
    const target = event.target
    target.value = (Number(target.value) + 3)

    comments.forEach(function (item){
        loadComments(lastElement, item)
    })

    if (target.value >= target.parentNode.querySelector('.btn-load-comments').value){
        target.setAttribute('hidden', '')
    }
}

async function loadCommentsRequest(event, id, offset, lastElement, func){
    return new Promise((resolve, reject) => {
        request(`/news/show/dop-comments?id=${id}&offset=${offset}`, 'get')
        .then(data  => {
            const comments = JSON.parse(data)
            if (func === 'first') {
                funcLoadFirstsComments(event, lastElement, comments)
                return resolve(true)
            }else if (func === 'last'){
                funcLoadLastComments(event, lastElement, comments)
                return resolve(true)
            }
        })
        .catch(error => {
            return reject(false)
        })
    })
}

function loadComments(lastElement, item){
    if (item.avatar === null){
        item.avatar = "resources/images/avatar/avatar_default.png"
    }
    lastElement.insertAdjacentHTML('beforebegin',
    `<div class="d-flex flex-start mt-4">
                <a class="me-3" href="#">
                    <img class="rounded-circle shadow-1-strong" src="/${item['avatar']}" alt="error" width="65" height="65">
                </a>
                <div class="flex-grow-1 flex-shrink-1">
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-1">
                                ${item['login']} <span class="small">- ${item['created_at']}</span>
                            </p>
                            <span class="small closer-class delete"> удалить</span>
                            <input hidden disabled value="dop">
                            <input hidden disabled value="${item.id}">
                        </div>
                        <p class="small mb-0">
                            ${item['text']}
                        </p>
                    </div>
                </div>
            </div>`
    )
}

function spinnerGrowOn(target){
    const spinner = target.parentNode.querySelector('.spinner-grow.load-anyway')
    spinner.removeAttribute('hidden')
}

function spinnerGrowOff(target){
    const spinner = target.parentNode.querySelector('.spinner-grow.load-anyway')
    spinner.setAttribute('hidden', '')
}

async function funcLoadComments(event){
    const target = event.target
    const targetParent = target.parentNode
    if (target.className === 'btn-load-comments'){
        spinnerGrowOn(event.target)
        const id = targetParent.id
        const lastElement = targetParent.querySelector('.btn-add-comments')
        const offset = 0

        if (target.textContent.indexOf('Скрыть комментарии') !== -1){
            spinnerGrowOff(target)
            const commentsForDeleted = targetParent.querySelectorAll('.d-flex.flex-start.mt-4')
            commentsForDeleted.forEach(function (item){
                item.remove()
            })
            targetParent.querySelector('.btn-add-comments').value = 3
            target.textContent = `Показать ответы: ${target.value}`
            targetParent.querySelector('.btn-add-comments').setAttribute('hidden', '')
            return
        }

        await loadCommentsRequest(event, id, offset, lastElement, 'first')
        spinnerGrowOff(target)
    }
    else if (target.className === 'btn-add-comments'){
        spinnerGrowOn(target)
        const id = targetParent.id
        const lastElement = targetParent.querySelector('.btn-add-comments')
        const offset = target.value

        await loadCommentsRequest(event, id, offset, lastElement, 'last')
        spinnerGrowOff(target)
    }
    else if (target.className === 'small closer-class'){
        inputComment(event)
    }
  	else if (target.className === 'small closer-class delete'){
        console.log(target.nextElementSibling)
        const typeItem = target.nextElementSibling
        const idItem = typeItem.nextElementSibling
        const type = typeItem.value
        const id = idItem.value
        const body = `type=${type}&id=${id}`
        const headers = {
            name: 'Content-Type',
            value: 'application/x-www-form-urlencoded'
        }
        request(`/news/show/delete-comment`, 'post', body, headers)
        .then(data  => {
            location.reload()
        })
        .catch(error => {
            console.log(error)
        })
    }
    else if (target.className === 'btn-load-comments leave-comment non-class'){
        const inputGroup = target.closest('.input-group')
        inputGroup.querySelector('.btn-load-comments.leave-comment.non-class-close').setAttribute('hidden', '')
        target.setAttribute('hidden', '')

        loadInputGroup(event, inputGroup)

        await funcLeaveDopComment(event)
        location.reload()
    }
    else if (target.className === 'btn-load-comments leave-comment non-class-close'){
        target.closest('.non-class').querySelector('.small.closer-class').removeAttribute('hidden')
        target.closest('.non-class-dop').remove()
    }
}

function funcLeaveComment(event){
    const target = event.target
    const inputGroup = target.closest('.input-group')
    loadInputGroup(event, inputGroup)

    const textarea = target.parentNode.querySelector('#leave-comment-field').value
    const body = `text=${textarea}&news_id=${target.value}`
    const headers = {
        name: 'Content-Type',
        value: 'application/x-www-form-urlencoded'
    }
    request('/news/comments/create', 'post', body, headers)
    .then(response => location.reload())
    .catch(response => console.log(response))
}

function funcLeaveDopComment(event){
    const target = event.target
    const comment_id = target.closest('.flex-grow-1.flex-shrink-1').id
    const textarea = target.parentNode.querySelector('#leave-comment-field').value
    const body = `text=${textarea}&news_id=${target.value}&comment_id=${comment_id}`
    const headers = {
        name: 'Content-Type',
        value: 'application/x-www-form-urlencoded'
    }
    request('/news/comments/create-dop', 'post', body, headers)
    .then(response => location.reload())
    .catch(response => console.log(response))
}

function inputComment(event){
    const target = event.target
    const parentTarget = target.closest('.non-class')
    const commentParentId = target.closest('.flex-grow-1.flex-shrink-1').id
    parentTarget.insertAdjacentHTML('beforeend',
    `
            <div class="non-class-dop">
                <div class="input-group">
                    <textarea class="form-control" id="leave-comment-field" aria-label="With textarea" style="width: 100%" placeholder="Введите коментарий"></textarea>
                        <button disabled class="btn-load-comments leave-comment non-class" style="width: 50%; margin-bottom: 10px" value="${commentParentId}">Ответить</button>
                        <button class="btn-load-comments leave-comment non-class-close" style="width: 50%; text-align: right" value="${commentParentId}">Отмена</button>
                </div>
            </div>`
    )
    parentTarget.querySelector('.form-control').addEventListener('input', (event) => blockSendWithoutTextarea(event))
    target.setAttribute('hidden', '')
}

function loadInputGroup(event, inputGroup){
    event.target.setAttribute('hidden', '')

    inputGroup.insertAdjacentHTML('afterend',
        `<div class="spinner-grow spinner-grow-sm" style="color: lightcoral" role="status"></div>
              <div class="spinner-grow spinner-grow-sm" style="color: lightcoral" role="status"></div>
              <div class="spinner-grow spinner-grow-sm" style="color: lightcoral" role="status"></div>`
    )
}

function blockSendWithoutTextarea(event){
    const target = event.target
    if (target.value === ''){
        target.nextElementSibling.setAttribute('disabled', '')
    }else{
        target.nextElementSibling.removeAttribute('disabled')
    }
}

const buttonLoadComments = document.querySelector('.comment-block')
if (buttonLoadComments){
    buttonLoadComments.addEventListener('click', (event) => funcLoadComments(event))
}

const leaveComment = document.querySelector('.btn-load-comments.leave-comment')
if (leaveComment){
    leaveComment.addEventListener('click', (event) => funcLeaveComment(event))
}

const blockSend = document.querySelector('.form-control')
if (blockSend){
    blockSend.addEventListener('input', (event) => blockSendWithoutTextarea(event))
}
