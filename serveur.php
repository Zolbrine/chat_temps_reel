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

// Ajout du socket dans le tableau des clients qui sont connectés
$clients = [];
$clients[] = $socket; 

while (true) {
    // Gestion des clients existants, $read = tableau des clients en cours de communication
    // $write et $except sont utilisées pour gérer les opérations d'écriture et les erreurs sur les sockets (pas utilisés) 
    $read = $clients;
    $write = null;
    $except = null;

    // socket_select() vérifie si des opérations de lecture sont possibles sur les sockets dans le tableau $read
    if (socket_select($read, $write, $except, 0) > 0) {
        foreach ($read as $client) {
            if ($client === $socket) {
                // Nouvelle connexion
                $newClient = socket_accept($socket);
                echo "Nouvelle connexion établie.\n";
                $clients[] = $newClient;
            } else {
                // Communication avec un client existant
                // Récupère le client en cours
                $index = array_search($client, $clients);
                
                // Récupérer si le client "exit" sur le message ou bien s'il ferme la fenêtre, s'il ferme la fenêtre
                $message = @socket_read($client, 1024);

                if ($message !== false) {
                    echo "Message reçu d'un client : $message\n";
                }elseif (trim($message) == 'exit' || $message === false){
                    echo "Client déconnecté.\n";
                    socket_close($client);
                    // Suppréssion du client qui part dans le tableau des clients
                    unset($clients[$index]);
                }
            }
        }
    }
}