const loadChatPage = require('./chatLoader');

test('chargement de la page de chat', () => {
    // Simulation de la fonction fetch pour retourner des données fictives
    global.fetch = jest.fn(() =>
        Promise.resolve({
            text: () => Promise.resolve('<div>Contenu du chat</div>'),
        })
    );

    // Appel de la fonction de chargement de la page de chat
    loadChatPage();

    // Vérification du contenu de l'élément chat-popup
    const chatPopup = document.getElementById('chat-popup');
    expect(chatPopup.innerHTML).toBe('<div>Contenu du chat</div>');
    expect(chatPopup.style.display).toBe('block');
});
