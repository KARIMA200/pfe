const express = require('express');
const promClient = require('prom-client');

const app = express();

// Créez un collecteur pour les métriques
const collecteur = new promClient.CollectorRegistry();

// Créez des métriques
const gauge = new promClient.Gauge({
    name: 'example_gauge',
    help: 'Example gauge metric',
    registers: [collecteur]
});

// Route pour exposer les métriques Prometheus
app.get('/metrics', (req, res) => {
    res.set('Content-Type', promClient.register.contentType);
    res.end(collecteur.metrics());
});

// Démarrez le serveur
app.listen(3000, () => {
    console.log('Serveur écoutant sur le port 3000');
});
