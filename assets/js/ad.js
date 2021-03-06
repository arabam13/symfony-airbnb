$('#add-image').click(function(){
    // je recupère le numéro des futurs champs que je vais créer
    const index = +$('#widgets-counter').val();

    // je récupère le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);

    //console.log(tmpl);

    //j'injecte ce code au sein de la div
    $('#annonce_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    // je gère le bouton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        //console.log(target);
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#annonce_images div.form-group').length;
    //console.log(count);
    $('#widgets-counter').val(count);

}

updateCounter();
handleDeleteButtons();