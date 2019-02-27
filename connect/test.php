
<?php

// Eléments d'authentification LDAP
$ldaprdn  = 'admin';     // DN ou RDN LDAP
$ldappass = 'bla';  // Mot de passe associé

// Connexion au serveur LDAP
$ldapconn = ldap_connect("localhost.bla.com")
    or die("Impossible de se connecter au serveur LDAP.");

if ($ldapconn) {

    // Connexion au serveur LDAP
    $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

    // Vérification de l'authentification
    if ($ldapbind) {
        echo "Connexion LDAP réussie...";
    } else {
        echo "Connexion LDAP échouée...";
    }

}

?>
