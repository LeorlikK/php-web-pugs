function cookieAudio(event){
    if (event.target.className === 'audio-player'){
        let currentDate = new Date();
        let currentYear = currentDate.getFullYear()
        currentDate.setMonth(currentYear + 1)
        document.cookie = `audio-volume=${event.target.volume}`
    }
}

const audio = document.querySelectorAll('audio')
if (audio){
    if (document.cookie.indexOf('audio-volume') !== -1) {
        const cookie = document.cookie.split(';')
        let cookieArray = {}
        cookie.forEach(function (item){
            const items = item.split('=')
            cookieArray[items[0].trim()] = items[1].trim()
        })

        audio.forEach(function (item){
            item.volume = cookieArray['audio-volume']
        })
    }
}
const audioVolume = document.querySelector('.row.mb-5')
if (audioVolume){
    audioVolume.addEventListener('input', (event) => cookieAudio(event))
}else {
    const audioVolume = document.querySelector('audio')
    if (audioVolume){
        console.log('TES')
        audioVolume.addEventListener('input', (event) => cookieAudio(event))
    }
}
