
$('.account__avatar-input').bind('change', function(event) {
    event.preventDefault();
    resetImage();
    let fileName = this.files[0].name;
    if(fileName.length > 15){
        $('.account__avatar-name').text(fileName.slice(0, 15) + '...');
    }else{
        $('.account__avatar-name').text(fileName);
    }
    $('.account__avatar-name').removeClass('invisible');
    $('.account__avatar-delete').removeClass('invisible');
    $('.account__avatar-submit').removeClass('invisible');
})

$('.account__image-input').bind('change', function(event) {
    event.preventDefault();
    resetAvatar();
    let fileName = this.files[0].name;
    if(fileName.length > 15){
        $('.account__image-name').text(fileName.slice(0, 15) + '...');
    }else{
        $('.account__image-name').text(fileName);
    }
    $('.account__image-name').removeClass('invisible');
    $('.account__image-delete').removeClass('invisible');
    $('.account__image-submit').removeClass('invisible');
})

$('.account__avatar-delete').bind('click', resetAvatar);

$('.account__image-delete').bind('click', resetImage);

function resetAvatar()
{
    $('.account__avatar-name').text('');
    $('.account__avatar-name').addClass('invisible');
    $('.account__avatar-delete').addClass('invisible');
    $('.account__avatar-submit').addClass('invisible');
    delete $('.account__avatar-input').files;
}

function resetImage()
{
    $('.account__image-name').text('');
    $('.account__image-name').addClass('invisible');
    $('.account__image-delete').addClass('invisible');
    $('.account__image-submit').addClass('invisible');
    delete $('.account__image-input').files;
}
