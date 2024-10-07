document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.querySelector('.memberinformationgeneratebtn');

    generateBtn.addEventListener('click', function() {
        window.print(); // This triggers the browser's print dialog to save as PDF
    });
});