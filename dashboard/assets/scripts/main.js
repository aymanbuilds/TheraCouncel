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

    addEventIfExists(openButton, 'click', () => popup.style.display = 'grid');
    closeButtons.forEach(button => addEventIfExists(button, 'click', () => popup.style.display = 'none'));
    closeUpdateButtons.forEach(button => addEventIfExists(button, 'click', () => updatePopup.style.display = 'none'));
    addEventIfExists(window, 'click', (event) => {
        if (event.target.classList.contains('popup-overlay')) {
            event.target.style.display = 'none';
        }
    });

    // Update & Delete Buttons Loop
    document.querySelectorAll('.blog-card').forEach((card) => {
        const updateButton = card.querySelector('.primary');
        const deleteButton = card.querySelector('.cancel');

        // Update Popup: Trigger when the edit button is clicked
        addEventIfExists(updateButton, 'click', () => {
            const blogId = updateButton.getAttribute('data-blog-id');
            document.getElementById('update-blog-id').value = blogId;

            fetch(`get_blog_data.php?id=${blogId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate the update form with the blog data
                        document.getElementById('update-blog-id').value = data.blog.id;
                        document.getElementById('update-title').value = data.blog.title;

                        tinymce.get('update-text').setContent(data.blog.content);
                        document.getElementById('update-text-hidden').value = data.blog.content;
                        const fileInput = document.getElementById('update-image');
                        fileInput.value = '';

                        const updatePopup = document.getElementById('update-popup');
                        if (updatePopup)
                            updatePopup.style.display = 'grid';
                    }
                })
                .catch(err => {
                    console.log('Error fetching blog data:', err);
                });

            if (updatePopup)
                updatePopup.style.display = 'grid';
        });

        // Delete Popup: Trigger when the delete button is clicked
        addEventIfExists(deleteButton, 'click', () => {
            const blogId = deleteButton.getAttribute('data-blog-id');
            confirmDeleteButton.setAttribute('data-blog-id', blogId);

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
    addEventIfExists(confirmDeleteButton, 'click', () => {
        const blogId = confirmDeleteButton.getAttribute('data-blog-id'); // Get blogId to delete

        // Send AJAX request to delete the blog
        fetch('delete_blog.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${blogId}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Successfully deleted, hide the popup and remove the blog card
                    deletePopup.style.display = 'none';
                    document.querySelector(`[data-blog-id="${blogId}"]`).remove(); // Remove blog from DOM

                    window.location.reload();
                }
            })
            .catch(err => {
                console.log('Error:', err);
            });
    });

    // Update Popup
    const updateForm = document.getElementById('update-form');

    addEventIfExists(window, 'click', (event) => {
        if (event.target.classList.contains('popup-overlay')) {
            if (updatePopup)
                updatePopup.style.display = 'none';
        }
    });

    // addEventIfExists(updateForm, 'submit', (e) => {
    // const content = tinymce.get('update-text').getContent();
    // document.getElementById('update-text').value = content; // Set the content back to the textarea
    // });

    if (document.querySelector('.rich-textbox')) {
        tinymce.init({
            selector: 'textarea.rich-textbox',
            plugins: 'link lists table image',
            license_key: 'gpl',
            convert_urls: false,
            toolbar: 'bold italic underline | fontsizeselect fontselect forecolor backcolor | link image | alignleft aligncenter alignright | numlist bullist',
            menubar: true,
            promotion: false,

            // Ensure the content is transferred to the textarea before form submission
            setup: function (editor) {
                editor.on('input', function () {
                    if (editor.id == 'update-text') {
                        document.getElementById('update-text-hidden').value = editor.getContent();
                    } else if (editor.id == 'blog-text') {
                        document.getElementById('blog-text-hidden').value = editor.getContent();
                    }
                });

                editor.on('ExecCommand', function () {
                    if (editor.id == 'update-text') {
                        document.getElementById('update-text-hidden').value = editor.getContent();
                    } else if (editor.id == 'blog-text') {
                        document.getElementById('blog-text-hidden').value = editor.getContent();
                    }
                });

                editor.on('NodeChange', function () {
                    if (editor.id == 'update-text') {
                        document.getElementById('update-text-hidden').value = editor.getContent();
                    } else if (editor.id == 'blog-text') {
                        document.getElementById('blog-text-hidden').value = editor.getContent();
                    }
                });
            }
        });
    }

    // User Popup
    if (document.getElementById('users-table')) {
        const userTable = document.getElementById('users-table').getElementsByTagName('tbody')[0];
        const addUserButton = document.getElementById('add-user');
        const addUserPopup = document.getElementById('add-user-popup');
        const updateUserPopup = document.getElementById('update-user-popup');
        const deleteUserPopup = document.getElementById('delete-popup');
        const userForm = document.getElementById('add-user-form');
        const closeAddUserPopupButton = document.querySelector('#add-user-popup .close-btn');
        const closeUpdateUserPopupButton = document.querySelector('#update-user-popup .close-btn');
        const closeDeleteUserPopupButton = document.querySelector('#delete-popup .close-btn');
        const userConfirmDeleteButton = document.getElementById('confirm-delete');
        const userCancelDeleteButton = document.getElementById('cancel-delete');
        let currentUserId = null; // To store the ID of the user being edited or deleted.

        // Mock user data (in a real app, you would fetch this from a server)
        let users = [];

        // Function to render users in the table
        function renderUserTable(dbUsers) {
            users = dbUsers;
            userTable.innerHTML = ''; // Clear the table

            users.forEach((user, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>
                    <div class='row'>
                        <button class="primary edit-btn" data-id="${user.id}">Edit</button>
                        <button class="cancel delete-btn" data-id="${user.id}">Delete</button>
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
            document.getElementById('user-name').value = '';
            document.getElementById('user-email').value = '';
            document.getElementById('user-password').value = '';
            currentUserId = null;
            document.getElementById('user-id').value = '';
            addUserPopup.style.display = 'grid'; // Show Add User popup
        });

        // Close Add User Popup
        addEventIfExists(closeAddUserPopupButton, 'click', () => {
            addUserPopup.style.display = 'none';
        });

        // Handle edit user
        function handleEditUser(event) {
            const userId = event.target.getAttribute('data-id'); // Get the user ID from the button

            const user = users.find(u => u.id === userId); // Find the user by ID

            // Populate the form fields with the user data
            document.getElementById('update-user-name').value = user.name;
            document.getElementById('update-user-email').value = user.email;
            document.getElementById('update-user-id').value = userId;  // Set user ID for updating

            // Display the popup
            updateUserPopup.style.display = 'grid';
        }

        // Handle delete user
        function handleDeleteUser(event) {
            const userId = event.target.getAttribute('data-id');
            currentUserId = userId;
            deleteUserPopup.style.display = 'grid'; // Show Delete Confirmation popup
        }

        // Confirm delete user
        addEventIfExists(userConfirmDeleteButton, 'click', () => {
            if (currentUserId !== null) {
                deleteUser(currentUserId);
            }
        });

        function deleteUser(userId) {
            const formData = new FormData();
            formData.append('user-id', userId);
            formData.append('delete-user', 'true');  // Trigger the delete user action

            fetch('delete_user.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        fetchUsers();
                    }
                })
                .catch(error => {

                });
        }

        // Cancel delete user
        addEventIfExists(userCancelDeleteButton, 'click', () => {
            deleteUserPopup.style.display = 'none';
        });

        // Close Delete User Popup
        addEventIfExists(closeDeleteUserPopupButton, 'click', () => {
            deleteUserPopup.style.display = 'none';
        });

        // Close Update User Popup
        addEventIfExists(closeUpdateUserPopupButton, 'click', () => {
            updateUserPopup.style.display = 'none';
        });

        // Handle Add or Update User Form Submission
        // userForm.addEventListener('submit', (e) => {
        //     e.preventDefault();
        //     const userId = document.getElementById('user-id').value;
        //     const name = document.getElementById('user-name').value;
        //     const email = document.getElementById('user-email').value;
        //     const password = document.getElementById('user-password').value;

        //     if (userId === "") {
        //         // Add New User
        //         users.push({ name, email, password });
        //     } else {
        //         // Update Existing User
        //         users[userId] = { name, email, password };
        //     }

        //     renderUserTable();
        //     addUserPopup.style.display = 'none';
        //     updateUserPopup.style.display = 'none';
        // });

        // Render initial users (if any)
        // renderUserTable();
        function fetchUsers() {
            fetch('get_users.php')  // Make sure this points to the PHP file that returns the users
                .then(response => response.json())
                .then(users => {
                    renderUserTable(users);
                })
                .catch(error => {
                    console.error("Error fetching users:", error);
                });
        }

        fetchUsers();
    }

    // Helper function to add event listeners if the element exists
    function addEventIfExists(element, event, callback) {
        if (element) {
            element.addEventListener(event, callback);
        }
    }
});
