<?php
// Définition des informations de connexion
const HOST = "127.0.0.1";
const PORT = 2222;

// Création du point de connexion du client
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// Connexion au serveur en mettant le point de communication, l'ip d'écoute, le port d'écoute
socket_connect($socket, HOST, PORT);

// Messages clients et définition du pseudo
echo "Connecté au serveur " . HOST . ":" . PORT . "...\n";
echo "Saisissez un pseudo:\n";
$pseudo = readline();

// Dans le cas ou le client mets pas de pseudo on le défini et messages clients
if (empty($pseudo)) {
    $pseudo = "user_inconnu";
}
echo "Saisissez un message:\n";

// Boucle qui permet d'envoyer des messages en boucle et en écrivant exit on ferme la connexion au socket
while (true) {
    $message = readline();
    if ($message === 'exit') {
        socket_write($socket, $message, 1000);
        socket_close($socket);
        break;
    }

    if (!empty($pseudo) && !empty($message)) {
        socket_write($socket, "$pseudo: $message", 1000);
        $message = "";
    }
}