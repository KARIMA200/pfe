const mysql = require('mysql');
const { getUserByEmail } = require('./uu');

// Configuration de la connexion à la base de données
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'ecommerce'
});

beforeAll(done => {
  connection.connect(done);
});

afterAll(done => {
  connection.end(done);
});

test('getUserByEmail returns correct user for existing email', done => {
  const email = 'example@example.com';
  getUserByEmail(connection, email, (err, user) => {
    expect(err).toBeNull();
    expect(user).toBeDefined();
    expect(user.email).toBe(email);
    done();
  });
});

test('getUserByEmail returns null for non-existing email', done => {
  const email = 'nonexisting@example.com';
  getUserByEmail(connection, email, (err, user) => {
    expect(err).toBeNull();
    expect(user).toBeNull();
    done();
  });
});
