<meta charset="UTF-8">

<?php

include 'connect.php';


function addUser(){
	
	$info["cn"] = "John Jones";
    $info["sn"] = "Jones";
    $info["objectclass"] = "person";
	$ldapconn = getLdapConnect();
    // Ajoute les données au dossier
    $r = ldap_add($ldapconn, "cn=John Jones, dc=bla, dc=com", $info);
}



//connect($ldapconn,"cn=admin,dc=bla,dc=com","bla")

addUser($ldapconn);


?>
