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
                        </div>
                        <p class="small mb-0">
                            ${item['text']}
                        </p>
                    </div>
                </div>
            </div>`
    )
}

// function addComment(lastElement, comment){
//     lastElement.insertAdjacentHTML('beforebegin',
//         `<div class="content">
//         <div class="d-flex flex-start">
//             <img class="rounded-circle shadow-1-strong me-3" src="/resources/images/avatar/sad-cat-37.jpg"
//                  alt="avatar" width="65" height="65">
//                 <div class="flex-grow-1 flex-shrink-1" id="9">
//                     <div>
//                         <div class="d-flex justify-content-between align-items-center">
//                             <p class="mb-1">Kakaraka <span class="small">- 5m ago</span></p>
//                             <a href="#!"><i class="fas fa-reply fa-xs"></i><span class="small"> reply</span></a>
//                         </div>
//                         <p class="small mb-0">
//                             qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq </p>
//                     </div>
//                     <button class="btn-add-comments" hidden="" value="3">Другие ответы</button>
//                     <div class="news-load-await">
//                         <div hidden="" class="spinner-grow load-anyway" style="margin-left: 43%" role="status">
//                             <span class="visually-hidden">Loading...</span>
//                         </div>
//                     </div>
//                 </div>
//         </div>
//     </div>`
//     )
// }

function funcLoadFirstsComments(event, lastElement, comments){
    if (event.target.textContent.indexOf('Показать ответы') !== -1){
        event.target.textContent = 'Скрыть комментарии'

        comments.forEach(function (item){
            loadComments(lastElement, item)
        })
        console.log(event.target.value, event.target.parentNode.querySelector('.btn-add-comments').value)
        if (event.target.value <= event.target.parentNode.querySelector('.btn-add-comments').value){
            console.log(event.target.parentNode.querySelector('.btn-add-comments'))
            event.target.parentNode.querySelector('.btn-add-comments').setAttribute('hidden', 'hidden')
            // event.target.parentNode.querySelector('.btn-add-comments').remove()
            // console.log(event.target.parentNode.querySelector('.btn-add-comments'))
        }else{
            event.target.parentNode.querySelector('.btn-add-comments').removeAttribute('hidden')
        }
    }
}

function funcLoadLastComments(event, lastElement, comments){
    event.target.value = (Number(event.target.value) + 3)
    comments.forEach(function (item){
        loadComments(lastElement, item)
    })
    if (event.target.value >= event.target.parentNode.querySelector('.btn-load-comments').value){
        event.target.setAttribute('hidden', '')
    }
}

async function loadCommentsRequest(event, id, offset, lastElement, func){
    return new Promise(function (resolve, reject){
        const router = new XMLHttpRequest()
        router.open('get', `/news/show/dop-comments?id=${id}&offset=${offset}`)
        router.onload = () => {
            if (router.response){
                const comments = JSON.parse(router.response)
                if (func === 'first'){
                    console.log('first')
                    funcLoadFirstsComments(event, lastElement, comments)
                    return resolve(true)
                }else if (func === 'last'){
                    console.log('last')
                    funcLoadLastComments(event, lastElement, comments)
                    return resolve(true)
                }
            }else{
                console.log('FALSE')
                return reject(false)
            }
        }
        router.send()
    })
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
    if (event.target.className === 'btn-load-comments'){
        console.log(event.target.textContent)
        spinnerGrowOn(event.target)
        const id = event.target.parentNode.id

        const lastElement = event.target.parentNode.querySelector('.btn-add-comments')
        console.log(event.target.parentNode.lastChild)

        const offset = 0

        if (event.target.textContent.indexOf('Скрыть комментарии') !== -1){
            spinnerGrowOff(event.target)
            const commentsForDeleted = event.target.parentNode.querySelectorAll('.d-flex.flex-start.mt-4')
            commentsForDeleted.forEach(function (item){
                item.remove()
            })
            event.target.parentNode.querySelector('.btn-add-comments').value = 3
            event.target.textContent = `Показать ответы: ${event.target.value}`
            event.target.parentNode.querySelector('.btn-add-comments').setAttribute('hidden', '')
            return
        }

        await loadCommentsRequest(event, id, offset, lastElement, 'first')
        spinnerGrowOff(event.target)
    }
    if (event.target.className === 'btn-add-comments'){
        spinnerGrowOn(event.target)
        const id = event.target.parentNode.id
        // const lastElement = event.target.parentNode.lastChild.previousSibling
        const lastElement = event.target.parentNode.querySelector('.btn-add-comments')
        const offset = event.target.value

        await loadCommentsRequest(event, id, offset, lastElement, 'last')
        spinnerGrowOff(event.target)
    }
    if (event.target.className === 'small closer-class'){
        test(event)
    }
    if (event.target.className === 'btn-load-comments leave-comment non-class'){
        console.log('CLICK')
        const inputGroup = event.target.closest('.input-group')
        inputGroup.querySelector('.btn-load-comments.leave-comment.non-class-close').setAttribute('hidden', '')
        event.target.setAttribute('hidden', '')

        loadInputGroup(event, inputGroup)

        await funcLeaveDopComment(event)
        console.log('END')
        location.reload()
    }
    if (event.target.className === 'btn-load-comments leave-comment non-class-close'){
        console.log('CLOSE')
        event.target.closest('.non-class').querySelector('.small.closer-class').removeAttribute('hidden')
        event.target.closest('.non-class-dop').remove()
    }
}

function funcLeaveComment(event){
    // if (event.target.parentNode.querySelector('#leave-comment-field').value === ''){
    //     event.target.parentNode.querySelector('#leave-comment-field').setAttribute('placeholder', 'Введите что-нибудь...')
    // }else {
    //     console.log(event.target.parentNode.querySelector('#leave-comment-field').value)

    const inputGroup = event.target.closest('.input-group')
    loadInputGroup(event, inputGroup)

    const textarea = event.target.parentNode.querySelector('#leave-comment-field').value
    const body = `text=${textarea}&news_id=${event.target.value}`
    const router = new XMLHttpRequest()
    router.open('post', `/news/comments/create`)
    router.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    router.onload = () => {
        if (router.response){
            console.log(router.response)
            location.reload()

            // const comments = document.querySelectorAll('.content')
            // const lastElement = comments[comments.length-1]
            // const response = JSON.parse(router.response)
            // console.log(response)
            // addComment(lastElement, comment)
        }else{
            console.log('FALSE')
        }
    }
    router.send(body)
}

function funcLeaveDopComment(event){
    console.log('START')

    const comment_id = event.target.closest('.flex-grow-1.flex-shrink-1').id
    const textarea = event.target.parentNode.querySelector('#leave-comment-field').value
    const body = `text=${textarea}&news_id=${event.target.value}&comment_id=${comment_id}`
    return new Promise(function (resolve, reject){
        const router = new XMLHttpRequest()
        router.open('post', `/news/comments/create-dop`)
        router.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        router.onload = () => {
            if (router.response){
                return resolve(true)
            }else{
                console.log('FALSE')
                return reject(false)
            }
        }
        router.send(body)
    })
}

function test(event){
    const parentTarget = event.target.closest('.non-class')
    console.log(parentTarget)
    const commentParentId = event.target.closest('.flex-grow-1.flex-shrink-1').id
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
    event.target.setAttribute('hidden', '')
    // const element = parentTarget.querySelector('.btn-load-comments.leave-comment.non-class')
    // element.addEventListener('click', () => testTwo)
    // console.log(element);
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
    if (event.target.value === ''){
        console.log('disable ON')
        event.target.nextElementSibling.setAttribute('disabled', '')
    }else{
        console.log('disable OFF')
        console.log(event.target.nextElementSibling)
        event.target.nextElementSibling.removeAttribute('disabled')
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
