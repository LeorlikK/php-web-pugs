function authorizationLogoutId(logout){
    console.log(logout);
    logout.insertAdjacentHTML('afterend', '<div class="spinner-grow" role="status">\n' +
        '  <span class="visually-hidden">Loading...</span>\n' +
        '</div>')
    const router = new XMLHttpRequest()
    router.open('post', '/logout')
    router.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    router.onload = () => {
        if (router.response){
            console.log(router.response)
            location.reload()
        }else{
            console.log('FALSE')
        }
    }
    router.send()
}

function scrollOnTop(){
    document.documentElement.scrollTop = 0
}

const logout = document.querySelector('#authorizationLogoutId')
if (logout) {
    logout.addEventListener('click', () => authorizationLogoutId(logout))
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
