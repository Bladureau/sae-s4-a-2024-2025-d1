document.addEventListener('DOMContentLoaded', function() {
    // Variables pour les éléments du formulaire
    const periodeTypeSelect = document.getElementById('periodeType');
    const moisSelect = document.getElementById('mois');
    const anneeSelect = document.getElementById('annee');
    const compareLastYearCheck = document.getElementById('compareLastYear');
    const chartTypeSelect = document.getElementById('chartType');
    const btnGenererComparaison = document.getElementById('btnGenererComparaison');

    // Instance de graphique
    let evolutionChart = null;

    // Active/désactive le sélecteur de mois en fonction du type de période
    periodeTypeSelect.addEventListener('change', function() {
        moisSelect.disabled = periodeTypeSelect.value !== 'day';
    });

    // Genere le graphique en fonction des paramètres sélectionnés
    btnGenererComparaison.addEventListener('click', function() {
        const periodeType = periodeTypeSelect.value;
        const annee = anneeSelect.value;
        const mois = moisSelect.value;
        const compareLastYear = compareLastYearCheck.checked;
        const chartType = chartTypeSelect.value;

        // Creation d'un objet FormData pour envoyer les données du formulaire
        const formData = new FormData();
        formData.append('periodeType', periodeType);
        formData.append('annee', annee);
        formData.append('mois', mois);
        formData.append('compareLastYear', compareLastYear ? '1' : '0');

        // Determine l'action en fonction du type de période
        const action = periodeType === 'month'
            ? 'getNotesFraisByMonth'
            : 'getNotesFraisByDay';

        // Requete AJAX pour récupérer les données du graphique
        fetch(`index.php?controller=AffichageListeNoteFrais&action=${action}`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log('Received data:', data); 
                createEvolutionChart(data, periodeType, compareLastYear, chartType);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des données:', error);
            });
    });

    // Fonction pour créer le graphique
    function createEvolutionChart(data, periodeType, compareLastYear, chartType) {
        // Preparation des labels en fonction du type de période
        let labels = [];
        if (periodeType === 'month') {
            // Utilisation l'API Intl pour récupérer les noms des mois en français
            const formatter = new Intl.DateTimeFormat('fr-FR', { month: 'long' });
            labels = Array.from({ length: 12 }, (_, i) => {
                const date = new Date(2023, i, 1);
                return formatter.format(date).charAt(0).toUpperCase() + formatter.format(date).slice(1);
            });
        } else {
            // Genere tous les jours du mois sélectionné
            const year = parseInt(anneeSelect.value);
            const month = parseInt(moisSelect.value);
            const daysInMonth = new Date(year, month, 0).getDate();

            for (let i = 1; i <= daysInMonth; i++) {
                labels.push(i.toString());
            }
        }

        // Recupère l'année actuelle et l'année précédente
        const currentYear = anneeSelect.value;
        const previousYear = parseInt(currentYear) - 1;

        const datasets = [];

        // Annee actuelle, montant et nombre
        datasets.push({
            label: `Montant ${currentYear}`,
            data: data.currentYear.montants,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            yAxisID: 'y'
        });

        datasets.push({
            label: `Nombre ${currentYear}`,
            data: data.currentYear.nombres,
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            type: 'line',
            yAxisID: 'y1'
        });

        // Si la comparaison est activée, 
        // ajoute les données de l'année précédente
        if (compareLastYear && data.previousYear) {
            datasets.push({
                label: `Montant ${previousYear}`,
                data: data.previousYear.montants,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderDash: [5, 5],
                yAxisID: 'y'
            });

            datasets.push({
                label: `Nombre ${previousYear}`,
                data: data.previousYear.nombres,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                borderDash: [5, 5],
                type: 'line',
                yAxisID: 'y1'
            });
        }

        // Récupère l'élément canvas
        const ctx = document.getElementById('evolutionChart').getContext('2d');

        // Supprime le graphique existant s'il existe
        if (evolutionChart) {
            evolutionChart.destroy();
        }

        // Crée un graphique en fonction du type de graphique sélectionné
        evolutionChart = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: periodeType === 'month' ? 'Mois' : 'Jour'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Montant (€)'
                        },
                        beginAtZero: true
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Nombre'
                        },
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw;

                                if (label.includes('Montant')) {
                                    return `${label}: ${new Intl.NumberFormat('fr-FR', {
                                        style: 'currency',
                                        currency: 'EUR'
                                    }).format(value)}`;
                                } else {
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            }
        });
    }
    btnGenererComparaison.click();
});