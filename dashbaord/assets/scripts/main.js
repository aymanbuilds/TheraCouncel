const popup = document.getElementById('blog-popup');
const openButton = document.getElementById('add-blog');
const closeButton = document.querySelector('.close-btn');
const form = document.getElementById('blog-form');

openButton.addEventListener('click', () => {
    popup.style.display = 'grid';
});

closeButton.addEventListener('click', () => {
    popup.style.display = 'none';
});

window.addEventListener('click', (event) => {
    if (event.target === popup) {
        popup.style.display = 'none';
    }
});

form.addEventListener('submit', (e) => {
    e.preventDefault();

    const blogImage = document.getElementById('blog-image').files[0];
    const blogTitle = document.getElementById('blog-title').value;
    const blogText = document.getElementById('blog-text').value;

    console.log('Blog Image:', blogImage);
    console.log('Blog Title:', blogTitle);
    console.log('Blog Text:', blogText);

    popup.style.display = 'none';
});


const updatePopup = document.getElementById('update-popup');
const openUpdateButton = document.getElementById('update-blog');
const closeUpdateButton = document.querySelector('#update-popup .close-btn');
const updateForm = document.getElementById('update-form');

openUpdateButton.addEventListener('click', () => {
    updatePopup.style.display = 'grid';
});

closeUpdateButton.addEventListener('click', () => {
    updatePopup.style.display = 'none';
});

window.addEventListener('click', (event) => {
    if (event.target === updatePopup) {
        updatePopup.style.display = 'none';
    }
});

updateForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const updateImage = document.getElementById('update-image').files[0];
    const updateTitle = document.getElementById('update-title').value;
    const updateText = document.getElementById('update-text').value;

    console.log('Updated Blog Image:', updateImage);
    console.log('Updated Blog Title:', updateTitle);
    console.log('Updated Blog Text:', updateText);

    updatePopup.style.display = 'none';
});

const deletePopup = document.getElementById('delete-popup');
const openDeleteButton = document.getElementById('delete-blog');
const cancelDeleteButton = document.getElementById('cancel-delete');
const confirmDeleteButton = document.getElementById('confirm-delete');

openDeleteButton.addEventListener('click', () => {
    deletePopup.style.display = 'grid';
});

cancelDeleteButton.addEventListener('click', () => {
    deletePopup.style.display = 'none';
});

window.addEventListener('click', (event) => {
    if (event.target === deletePopup) {
        deletePopup.style.display = 'none';
    }
});

confirmDeleteButton.addEventListener('click', () => {
    deletePopup.style.display = 'none';
});


if (document.querySelector('.rich-textbox')) {
    tinymce.init({
        selector: 'textarea.rich-textbox',
        plugins: 'link lists table image',
        license_key: 'gpl',
        convert_urls: false,
        toolbar: 'bold italic underline | fontsizeselect fontselect forecolor backcolor | link image | alignleft aligncenter alignright | numlist bullist',
        menubar: true,
        promotion: false
    });
}
