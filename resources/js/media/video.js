function cookieVideo(event){
    const className = event.target.className
    if (className === 'video-player' || className === 'video-player img-thumbnail admin-video-edit'){
        let currentDate = new Date();
        let currentYear = currentDate.getFullYear()
        currentDate.setMonth(currentYear + 1)
        document.cookie = `video-volume=${event.target.volume}`
    }
}

const video = document.querySelectorAll('video')
if (video){
    if (document.cookie.indexOf('video-volume') !== -1) {
        const cookie = document.cookie.split(';')
        let cookieArray = {}
        cookie.forEach(function (item){
            const items = item.split('=')
            cookieArray[items[0].trim()] = items[1].trim()
        })

        video.forEach(function (item){
            item.volume = cookieArray['video-volume']
        })
    }
}

const videoVolume = document.querySelector('.row.mb-5')
if (videoVolume){
    videoVolume.addEventListener('input', (event) => cookieVideo(event))
}else {
    const videoVolume = document.querySelector('video')
    if (videoVolume){
        videoVolume.addEventListener('input', (event) => cookieVideo(event))
    }
}