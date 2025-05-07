document.addEventListener("DOMContentLoaded", function(){
    // Chargement des projets et événements depuis get_items.php
    fetch('php/get_items.php')
    .then(response => response.json())
    .then(items => {
        const container = document.getElementById('project-container');
        if (!items || items.length === 0) {
            container.innerHTML = '<p>Aucun projet ou événement disponible.</p>';
        } else {
            items.forEach(item => {
                const card = document.createElement('div');
                card.className = 'project-card'; // Utilisation du style déjà défini
                let photoHTML = '';
                if (item.photo && item.photo.trim() !== "") {
                    photoHTML = `<img src="${item.photo}" alt="${item.title}">`;
                }
                
                card.innerHTML = `
                    ${photoHTML}
                    <h3>${item.title}</h3>
                `;
                container.appendChild(card);
            });
        }
    })
    .catch(error => {
        console.error('Erreur dans le chargement des projets et événements :', error);
    });
});
