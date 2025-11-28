document.addEventListener('DOMContentLoaded', function() {
    const profilePhotoInput = document.getElementById('profile_photo');

    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const profileImage = document.querySelector('img[alt="Profile"]');
                    const profileInitial = document.querySelector('.rounded-circle');

                    if (profileImage) {
                        profileImage.src = e.target.result;
                    } else if (profileInitial) {
                        profileInitial.style.backgroundImage = `url(${e.target.result})`;
                        profileInitial.style.backgroundSize = 'cover';
                        profileInitial.style.backgroundPosition = 'center';
                    }
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
});
