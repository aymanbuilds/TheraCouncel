function addEventIfExists(element, event, handler) {
    if (element) {
        element.addEventListener(event, handler);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Blog Popup
    const popup = document.getElementById('blog-popup');
    const updatePopup = document.getElementById('update-popup');
    const openButton = document.getElementById('add-blog');
    const closeButtons = document.querySelectorAll('#blog-popup .close-btn');
    const closeUpdateButtons = document.querySelectorAll('#update-popup .close-btn');
    const form = document.getElementById('blog-form');

    addEventIfExists(openButton, 'click', () => popup.style.display = 'grid');
    closeButtons.forEach(button => addEventIfExists(button, 'click', () => popup.style.display = 'none'));
    closeUpdateButtons.forEach(button => addEventIfExists(button, 'click', () => updatePopup.style.display = 'none'));
    addEventIfExists(window, 'click', (event) => {
        if (event.target.classList.contains('popup-overlay')) {
            event.target.style.display = 'none';
        }
    });

    addEventIfExists(form, 'submit', (e) => {
        e.preventDefault();
        console.log('Blog Image:', document.getElementById('blog-image')?.files[0]);
        console.log('Blog Title:', document.getElementById('blog-title')?.value);
        console.log('Blog Text:', document.getElementById('blog-text')?.value);
        popup.style.display = 'none';
    });

    // Update & Delete Buttons Loop
    document.querySelectorAll('.blog-card').forEach((card) => {
        const updateButton = card.querySelector('.primary');
        const deleteButton = card.querySelector('.cancel');

        // Update Popup
        addEventIfExists(updateButton, 'click', () => {
            if (updatePopup)
                updatePopup.style.display = 'grid';
        });

        // Delete Popup
        addEventIfExists(deleteButton, 'click', () => {
            const deletePopup = document.getElementById('delete-popup');
            if (deletePopup)
                deletePopup.style.display = 'grid';
        });
    });

    // Delete Popup
    const deletePopup = document.getElementById('delete-popup');
    const cancelDeleteButton = document.getElementById('cancel-delete');
    const confirmDeleteButton = document.getElementById('confirm-delete');

    addEventIfExists(cancelDeleteButton, 'click', () => deletePopup.style.display = 'none');
    addEventIfExists(window, 'click', (event) => {
        if (event.target.classList.contains('popup-overlay')) {
            event.target.style.display = 'none';
        }
    });
    addEventIfExists(confirmDeleteButton, 'click', () => deletePopup.style.display = 'none');

    // Update Popup
    const updateForm = document.getElementById('update-form');

    addEventIfExists(window, 'click', (event) => {
        if (event.target.classList.contains('popup-overlay')) {
            if (updatePopup)
                updatePopup.style.display = 'none';
        }
    });

    addEventIfExists(updateForm, 'submit', (e) => {
        e.preventDefault();
        console.log('Updated Blog Image:', document.getElementById('update-image')?.files[0]);
        console.log('Updated Blog Title:', document.getElementById('update-title')?.value);
        console.log('Updated Blog Text:', document.getElementById('update-text')?.value);
        updatePopup.style.display = 'none';
    });

    // TinyMCE Initialization
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

    // User Popup
    // Variables with "user" suffix to avoid conflicts with blog popups
    const userTable = document.getElementById('users-table').getElementsByTagName('tbody')[0];
    const addUserButton = document.getElementById('add-user');
    const userPopup = document.getElementById('user-popup');
    const deleteUserPopup = document.getElementById('delete-popup');
    const userForm = document.getElementById('user-form');
    const closeUserPopupButton = document.querySelector('#user-popup .close-btn');
    const closeDeleteUserPopupButton = document.querySelector('#delete-popup .close-btn');
    const userConfirmDeleteButton = document.getElementById('confirm-delete');
    const userCancelDeleteButton = document.getElementById('cancel-delete');
    let currentUserId = null; // To store the ID of the user being edited or deleted.

    // Mock user data (in a real app, you would fetch this from a server)
    let users = [
        {
            name: 'John Doe',
            email: 'johndoe@example.com',
            password: 'password123'
        },
        {
            name: 'Jane Smith',
            email: 'janesmith@example.com',
            password: 'securepassword456'
        },
        {
            name: 'Alice Johnson',
            email: 'alicejohnson@example.com',
            password: 'mypassword789'
        },
        {
            name: 'John Doe',
            email: 'johndoe@example.com',
            password: 'password123'
        },
        {
            name: 'Jane Smith',
            email: 'janesmith@example.com',
            password: 'securepassword456'
        },
        {
            name: 'Alice Johnson',
            email: 'alicejohnson@example.com',
            password: 'mypassword789'
        },
        {
            name: 'John Doe',
            email: 'johndoe@example.com',
            password: 'password123'
        },
        {
            name: 'Jane Smith',
            email: 'janesmith@example.com',
            password: 'securepassword456'
        },
        {
            name: 'Alice Johnson',
            email: 'alicejohnson@example.com',
            password: 'mypassword789'
        }
    ];

    // Function to render users in the table
    function renderUserTable() {
        userTable.innerHTML = ''; // Clear the table

        users.forEach((user, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>
                    <div class='row'>
                        <button class="primary edit-btn" data-id="${index}">Edit</button>
                        <button class="cancel delete-btn" data-id="${index}">Delete</button>
                    </div>
                </td>
            `;
            userTable.appendChild(row);
        });

        // Attach event listeners for edit and delete buttons
        document.querySelectorAll('.edit-btn').forEach(button => {
            addEventIfExists(button, 'click', handleEditUser);
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            addEventIfExists(button, 'click', handleDeleteUser);
        });
    }

    // Add user
    addEventIfExists(addUserButton, 'click', () => {
        document.getElementById('popup-title').textContent = 'Add User';
        document.getElementById('user-name').value = '';
        document.getElementById('user-email').value = '';
        document.getElementById('user-password').value = '';
        currentUserId = null;
        userPopup.style.display = 'grid';
    });

    // Submit user form (Add/Update)
    addEventIfExists(userForm, 'submit', (e) => {
        e.preventDefault();

        const name = document.getElementById('user-name').value;
        const email = document.getElementById('user-email').value;
        const password = document.getElementById('user-password').value;

        if (currentUserId === null) {
            // Add user
            users.push({ name, email, password });
        } else {
            // Update user
            users[currentUserId] = { name, email, password };
        }

        userPopup.style.display = 'none';
        renderUserTable();
    });

    // Close user popup
    addEventIfExists(closeUserPopupButton, 'click', () => {
        userPopup.style.display = 'none';
    });

    // Handle edit user
    function handleEditUser(event) {
        const userId = event.target.getAttribute('data-id');
        const user = users[userId];

        document.getElementById('popup-title').textContent = 'Update User';
        document.getElementById('user-name').value = user.name;
        document.getElementById('user-email').value = user.email;
        document.getElementById('user-password').value = user.password;

        currentUserId = userId;
        userPopup.style.display = 'grid';
    }

    // Handle delete user
    function handleDeleteUser(event) {
        const userId = event.target.getAttribute('data-id');
        currentUserId = userId;
        deleteUserPopup.style.display = 'grid';
    }

    // Confirm delete user
    addEventIfExists(userConfirmDeleteButton, 'click', () => {
        if (currentUserId !== null) {
            users.splice(currentUserId, 1);
            renderUserTable();
            deleteUserPopup.style.display = 'none';
        }
    });

    // Cancel delete user
    addEventIfExists(userCancelDeleteButton, 'click', () => {
        deleteUserPopup.style.display = 'none';
    });

    // Close delete user popup
    addEventIfExists(closeDeleteUserPopupButton, 'click', () => {
        deleteUserPopup.style.display = 'none';
    });

    // Render initial users (if any)
    renderUserTable();
});
