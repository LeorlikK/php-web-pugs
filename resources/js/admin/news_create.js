const inputTitleId = document.querySelector('#titleInputId')
const inputTitleFakeId = document.querySelector('#inputTitleFakeId')
inputTitleId.addEventListener('input', () => {
    inputTitleFakeId.textContent = inputTitleId.value
})

const inputShortId = document.querySelector('#shortInputId')
const inputShortFakeId = document.querySelector('#inputShortFakeId')
inputShortId.addEventListener('input', () => {
    inputShortFakeId.textContent = inputShortId.value
})

$(document).ready(function() {
    const textForSummerText = document.getElementById('innerTextId')
    let summernote = $('#summernote').summernote();
    let text = textForSummerText.value
    summernote.summernote('code', text);
});
