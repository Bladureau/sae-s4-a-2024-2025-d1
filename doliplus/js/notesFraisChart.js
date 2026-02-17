document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du graphique en camembert
    loadPieChartData();

    // Ajout d'un écouteur d'événements pour le bouton de filtre
    const btnFiltrerNotesFrais = document.getElementById('btnFiltrerNotesFrais');
    if (btnFiltrerNotesFrais) {
        btnFiltrerNotesFrais.addEventListener('click', function() {
            loadPieChartData();
        });
    }

    function loadPieChartData() {
        // Recupération des valeurs des filtres
        const dateDebut = document.getElementById('dateDebut')?.value || '';
        const dateFin = document.getElementById('dateFin')?.value || '';

        // Creation d'un objet FormData pour envoyer 
        // les données du formulaire
        const formData = new FormData();
        if (dateDebut) formData.append('dateDebut', dateDebut);
        if (dateFin) formData.append('dateFin', dateFin);

        // Requete pour récupérer les données du graphique
        fetch('index.php?controller=AffichageListeNoteFrais&action=getStatisticsData', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Pie chart data:', data);
                createPieChart(data);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des données:', error);
            });
    }
    // Fonction pour créer le graphique en camembert
    function createPieChart(data) {
        const ctx = document.getElementById('notesFraisChart');
        if (!ctx) {
            console.error('Canvas element not found');
            return;
        }

        // check si data est vide
        if (!data || data.length === 0) {
            console.error('No data received for pie chart');
            return;
        }

        // Extrait les libellés et les données pour le graphique
        const labels = data.map(item => item.libelle);
        const values = data.map(item => item.total_ttc);
        const counts = data.map(item => item.nombre);

        const backgroundColors = generateColors(data.length);

        // Supprime le graphique existant s'il existe
        if (window.pieChart) {
            window.pieChart.destroy();
        }

        // cree un graphique en camembert
        window.pieChart = new Chart(ctx.getContext('2d'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const index = context.dataIndex;
                                const count = counts[index];
                                const formattedValue = new Intl.NumberFormat('fr-FR', {
                                    style: 'currency',
                                    currency: 'EUR'
                                }).format(value);

                                return [
                                    `${label}: ${formattedValue}`,
                                    `Nombre: ${count} note(s)`
                                ];
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    function generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * 137) % 360; 
            colors.push(`hsla(${hue}, 70%, 60%, 0.8)`);
        }
        return colors;
    }
});