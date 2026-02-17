console.log('fournisseursChart.js chargé');
document.addEventListener('DOMContentLoaded', function() {
    // Variables pour les éléments du formulaire
    var ctx = document.getElementById('fournisseursChart').getContext('2d');
    var chartInstance = null;
    var fournisseursTable = document.querySelector('.fournisseurs-table tbody');
    var originalTableRows = Array.from(fournisseursTable.querySelectorAll('tr:not(.liste_titre)'));
    var countRow = document.createElement('tr');
    var countCell = document.createElement('td');
    countCell.colSpan = 3;
    countRow.appendChild(countCell);

    // Récupération des données des fournisseurs
    function updateDisplay(topCount) {
        var sortedData = [...fournisseursData].sort((a, b) => {
            var valueA = parseFloat(a.total);
            var valueB = parseFloat(b.total);
            return valueB - valueA;
        });

        var dataToShow = topCount > 0 ? sortedData.slice(0, topCount) : sortedData;

        updateChart(dataToShow);
        updateTable(dataToShow);
        updateCountDisplay(dataToShow.length);
    }
    // Fonction pour créer le graphique
    function updateChart(data) {
        var labels = data.map(f => f.fournisseur);
        var chartData = data.map(f => parseFloat(f.total));

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Chiffre d\'affaires',
                    data: chartData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    // Fonction pour mettre à jour le tableau
    function updateTable(data) {
        while (fournisseursTable.firstChild) {
            fournisseursTable.removeChild(fournisseursTable.firstChild);
        }

        const headerRow = document.createElement('tr');
        headerRow.className = 'liste_titre';
        headerRow.innerHTML = `
            <th class="liste_titre">Classement</th>
            <th class="liste_titre">Nom</th>
            <th class="liste_titre">Chiffre d'affaires</th>
        `;
        fournisseursTable.appendChild(headerRow);

        data.forEach((fournisseur, index) => {
            const row = document.createElement('tr');
            const chiffreAffaire = parseFloat(fournisseur.total);

            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${fournisseur.fournisseur}</td>
                <td>${new Intl.NumberFormat('fr-FR').format(chiffreAffaire)} €</td>
            `;
            fournisseursTable.appendChild(row);
        });

        fournisseursTable.appendChild(countRow);
    }

    function updateCountDisplay(count) {
        countCell.innerHTML = `<span class="opacitymedium">${count} élément(s) trouvé(s)</span>`;
    }

    // Retire la valeur stockée dans le stockage local
    var storedTopValue = localStorage.getItem('topSelectorValue');
    // Si une valeur est trouvée, on la met à jour
    if (storedTopValue) {
        document.getElementById('topSelector').value = storedTopValue;
        updateDisplay(parseInt(storedTopValue));
        // Sinon, on met à jour avec la valeur par défaut
    } else {
        document.getElementById('topSelector').value = "0";
        updateDisplay(0);
    }
    // Ajoute un event listener pour le changement de valeur
    document.getElementById('topSelector').addEventListener('change', function() {
        var selectedValue = this.value;
        localStorage.setItem('topSelectorValue', selectedValue);
        updateDisplay(parseInt(selectedValue));
    });
});