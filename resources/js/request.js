function request(url, method, body=null, headers=null){
    return new Promise((resolve, reject) => {
        const xml = new XMLHttpRequest()
        xml.open(method, url)
        if (headers){
            xml.setRequestHeader(headers.name, headers.value)
        }
        xml.onload = () => {
            resolve(xml.response)
        }
        xml.onerror = () => {
            reject(xml.status)
        }
        xml.send(body)
    })
}

export {request}