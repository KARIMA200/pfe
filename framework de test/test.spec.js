const axios = require('axios');

test('fetching products returns data', async () => {
  const response = await axios.get('http://localhost:8000/produits');
  expect(response.status).toBe(200);
  expect(response.data.length).toBeGreaterThan(0);
});

test('adding product to cart works', async () => {
  const productId = 1;
  const response = await axios.post('http://localhost:8000/ajouter_panier', { productId });
  expect(response.status).toBe(200);
  expect(response.data.success).toBe(true);
});

test('sending message works', async () => {
  const productId = 1;
  const response = await axios.post('http://localhost:8000/envoyer_message', { productId });
  expect(response.status).toBe(200);
  expect(response.data.success).toBe(true);
});
