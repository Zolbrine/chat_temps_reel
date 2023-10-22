<?php
// Définition des informations du SERVEUR
const HOST = "127.0.0.1";
const PORT = 2222;

// Création du point de communication
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// Lier le socket à l'ip et au port correspondant
socket_bind($socket, HOST, PORT);

// Mettre le serveur à l'écoute des nouvelles connexions sur le socket correspondant
socket_listen($socket);
echo "Serveur en écoute sur " . HOST . ":" . PORT . "...\n";

// Ajout du socket du serveur dans le tableau des clients 
$clients = [];
$clients[] = $socket; 

while (true) {
    // Gestion des clients existants
    $read = $clients;
    $write = null;
    $except = null;

    if (socket_select($read, $write, $except, 0) > 0) {
        foreach ($read as $client) {
            if ($client === $socket) {
                // Nouvelle connexion
                $newClient = socket_accept($socket);
                echo "Nouvelle connexion établie.\n";
                $clients[] = $newClient;
            } else {
                // Communication avec un client existant
                $index = array_search($client, $clients);
                $message = socket_read($client, 1024);

                if ($message === false || trim($message) == 'exit') {
                    echo "Client déconnecté.\n";
                    socket_close($client);
                    unset($clients[$index]);
                } else {
                    echo "Message reçu d'un client : $message\n";
                }
            }
        }
    }
}