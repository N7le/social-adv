document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-copy-share-url]');

    if (!button) {
        return;
    }

    const status = button.parentElement.querySelector('[data-share-status]');

    if (!navigator.clipboard) {
        status.textContent = 'Clipboard access is unavailable in this browser.';

        return;
    }

    navigator.clipboard.writeText(button.dataset.copyShareUrl)
        .then(() => {
            status.textContent = 'Post link copied to clipboard.';
        })
        .catch(() => {
            status.textContent = 'Could not copy the post link. Check clipboard permissions.';
        });
});

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('postMedia');

    if (!input) {
        return;
    }

    const preview = document.getElementById('postMediaPreview');
    const image = document.getElementById('postMediaPreviewImage');
    const video = document.getElementById('postMediaPreviewVideo');
    const name = document.getElementById('postMediaName');
    const removeButton = document.getElementById('postMediaRemove');
    let objectUrl = null;

    const reset = () => {
        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        }

        image.src = '';
        image.classList.add('d-none');
        video.removeAttribute('src');
        video.load();
        video.classList.add('d-none');
        name.textContent = '';
        preview.classList.add('d-none');
    };

    input.addEventListener('change', () => {
        const file = input.files && input.files[0];

        if (!file) {
            reset();

            return;
        }

        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
        }

        objectUrl = URL.createObjectURL(file);
        name.textContent = file.name;

        if (file.type.startsWith('image/')) {
            image.src = objectUrl;
            image.classList.remove('d-none');
            video.classList.add('d-none');
        } else if (file.type.startsWith('video/')) {
            video.src = objectUrl;
            video.classList.remove('d-none');
            image.classList.add('d-none');
        }

        preview.classList.remove('d-none');
    });

    removeButton.addEventListener('click', () => {
        input.value = '';
        reset();
    });
});

const userId = document.head.querySelector('meta[name="auth-user-id"]').content;
if (userId) {
    window.Echo.private(`users.${userId}`)
        .listen('.post.notification.created', (notification) => {
            showNotificationToast(notification.message);
        });
}

function showNotificationToast(message) {
    const container = document.getElementById('notificationToasts');

    if (!container) {
        return;
    }

    const toast = document.createElement('div');
    toast.className = 'toast align-items-center';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>`;
    toast.querySelector('.toast-body').textContent = message;

    container.appendChild(toast);
    toast.addEventListener('hidden.bs.toast', () => toast.remove());

    new bootstrap.Toast(toast).show();
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
