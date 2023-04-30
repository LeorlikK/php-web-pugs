import {request} from "/resources/js/request.js";

function authorizationLogoutId(logout){
    console.log('lalalaa')
    // logout.insertAdjacentHTML('afterend', '<div class="spinner-grow" role="status">\n' +
    //     '  <span class="visually-hidden">Loading...</span>\n' +
    //     '</div>')
    const headers = {
        name: 'Content-Type',
        value: 'application/x-www-form-urlencoded'
    }
    request(`/logout`, 'post', null, headers)
    .then(data => {
        location.reload()
    })
    .catch(error => {
        console.log(error)
        return reject(false)
    })
}

function scrollOnTop(){
    document.documentElement.scrollTop = 0
}

const logout = document.querySelector('#authorizationLogoutId')
if (logout) {
    logout.addEventListener('click', () => authorizationLogoutId(logout))
}else {
    console.log('NONONO')
}

const topScroll = document.querySelector('.top')
if (topScroll) {
    topScroll.addEventListener('click', () => scrollOnTop())
}

const success = document.querySelector('.alert.alert-danger')
console.log(success)
if (success) {
    setTimeout(function(){
        success.style.opacity = "0"
    }, 3000);
}
