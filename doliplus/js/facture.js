console.log("Chargement du fichier factureeee.js");

document.addEventListener("DOMContentLoaded", function() {
    console.log("Script intégré chargé");

    // Ajoute un event listener global pour capter les clics sur tous les boutons
    document.body.addEventListener("click", function(event) {
        // Si le clic est sur un bouton de détails
        if (event.target.matches(".btn-details")) {
            event.preventDefault();

            let id = event.target.getAttribute("data-id");
            let details = document.getElementById(id);
            // Si l'élément est trouvé, on bascule la classe hidden
            if (details) {
                details.classList.toggle("hidden");
                console.log("Toggle facture: " + id);
            } else {
                console.error("Élément non trouvé:", id);
            }
        }
    });
});

