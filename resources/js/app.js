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

