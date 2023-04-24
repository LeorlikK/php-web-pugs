function preview(){
    const input = document.querySelector('#examplePhotos').files[0]
    const image = document.querySelector('#imgPreviewId')
    const reader = new FileReader();

    reader.onloadend = function () {
        image.src = reader.result;
    }
    if (input){
        reader.readAsDataURL(input)
    }else{
        image.src = '#'
    }
}

const inputImage = document.querySelector('#examplePhotos').defaultValue
const imageView = document.querySelector('#imgPreviewId')
if (inputImage !== ''){
    imageView.src = '/' + inputImage
}else {
    let imageEdit = document.querySelector('#imageEditWhenLoadIsNotId')
    if(imageEdit !== null && 'value' in imageEdit){
        imageEdit = imageEdit.value
        imageView.src = '/' + imageEdit
    }else{
        imageView.src = '#'
    }
}