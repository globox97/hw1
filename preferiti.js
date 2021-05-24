function espandi(event) {
    event.currentTarget.parentNode.querySelector('ol').classList.remove('hidden');
    event.currentTarget.parentNode.querySelector('.titolo').textContent = 'Clicca qui per nascondere la lista dei brani';
    event.currentTarget.removeEventListener('click', espandi);
    event.currentTarget.addEventListener('click', riduci);
}

function riduci(event) {
    event.currentTarget.parentNode.querySelector('ol').classList.add('hidden');
    event.currentTarget.parentNode.querySelector('.titolo').textContent = 'Clicca qui per vedere i brani';
    event.currentTarget.removeEventListener('click', riduci);
    event.currentTarget.addEventListener('click', espandi);
}

function filtra(event) {
    const albums = document.querySelectorAll('.contenuto');
    for(let album of albums) {
        if(album.querySelector('span').textContent.toLowerCase().indexOf(event.currentTarget.value.toLowerCase()) === -1) {
            album.classList.add('hidden');
            const brani = album.querySelectorAll('li');
            for(let brano of brani){
                if(brano.textContent.toLowerCase().indexOf(event.currentTarget.value.toLowerCase()) !== -1) {
                    album.classList.remove('hidden');
                }
            }    
        }
        else album.classList.remove('hidden');
        if(event.currentTarget.value === '') {
            album.classList.remove('hidden');
        }

    }
    
}

function removeFavorite(event) {
    const id = event.currentTarget.parentNode.querySelector('#id-fav').innerHTML;
    const type = event.currentTarget.parentNode.querySelector('#tipo').innerHTML;
    fetch("delete-fav.php?id="+encodeURIComponent(id)+"&type="+encodeURIComponent(type));
    location.reload();
}

function redirect(event) {
    window.location.href = "home.php";
}

function onResponse(response) {
    return response.json();
}

function onFavJson(json) {
    if(json.entries().length === 0) {
        console.log("Errore, nessun preferito trovato");
    } else {
        console.log(json);
        document.querySelector('section').innerHTML = '';
        for(elemento of json) {
            if(elemento.type == 'album' || elemento.type == 'artist') {
                const contenuto = document.createElement('div');
                contenuto.classList.add('contenuto');
                const img_risultato = document.createElement('img');
                img_risultato.src = elemento.images[0].url;
                contenuto.appendChild(img_risultato);
                let nome_risultato = document.createElement('span');
                nome_risultato.innerHTML = elemento.name;
                nome_risultato.classList.add('nome');
                contenuto.appendChild(nome_risultato);
                const id = document.createElement('span');
                id.classList.add('hidden');
                id.id = 'id-fav';
                id.textContent = elemento.id;
                contenuto.appendChild(id)
                if(elemento.type === 'album' && elemento.album_type === 'album') {
                    const titolo = document.createElement('span');
                    titolo.classList.add('titolo');
                    titolo.textContent = 'Clicca qui per vedere la lista dei brani';
                    titolo.addEventListener('click', espandi);
                    contenuto.appendChild(titolo);
                    const brani = elemento.tracks.items;
                    const lista = document.createElement('ol');
                    lista.classList.add('hidden');
                    for(traccia of brani) {
                        const titolo_brano = document.createElement('li');
                        titolo_brano.textContent = traccia.name;
                        lista.appendChild(titolo_brano);
                    }
                    contenuto.appendChild(lista);
                }
                const tipo = document.createElement('span');
                tipo.id = 'tipo';
                tipo.textContent = elemento.type;
                tipo.classList.add('hidden');
                contenuto.appendChild(tipo);
                const img_fav = document.createElement('img');
                img_fav.src = 'https://cdn2.iconfinder.com/data/icons/picons-essentials/57/favorite_remove-512.png'
                img_fav.addEventListener('click', removeFavorite);
                img_fav.classList.add('add-fav');
                contenuto.appendChild(img_fav);
                document.querySelector('section').appendChild(contenuto);
            }
        }
    }
}

document.querySelector('#home_redirect').addEventListener('click', redirect);
const username = document.querySelector('#user').value;
console.log("Utente loggato: " + username);
fetch("get-fav.php?q="+username).then(onResponse).then(onFavJson);
document.querySelector('input').addEventListener('keyup', filtra);