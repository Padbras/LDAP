
<meta charset="UTF-8">

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

 /***
  * 
  * https://www.theurbanpenguin.com/managing-openldap-users-with-php/
  * 
  * user : add, rm, modifier
  * group : add, remove,modifier
  * connexion : admin -> all
  * user -> seulement modifier
  * 
  * */
  
  // ADDING
  
	function addUser($ldapconn,$firstname,$lastname,$pwd){
	// TODO Link à Formulaire l'entrée des donnees
	$r = rand(1000,9999);
	$dn = "uid=".$firstname.",ou=people,dc=bla,dc=com";
	$info["objectClass"][0] = "top";
    $info["objectClass"][1] = "person";
    $info["objectClass"][2] = "organizationalPerson";
	$info["objectClass"][3] = "inetOrgPerson";
    $info["objectClass"][4] = "posixAccount";
    $info["objectClass"][5] = "shadowAccount";
    
    $info["sn"] = $lastname;
    $info["givenName"] = $firstname;
    $info["cn"] = $lastname. " ". $firstname;
	$info["userPassword"] = $pwd;
    $info["homeDirectory"] = "/home/".$firstname;
    $info["uidNumber"] = $r;
    $info["gidNumber"] = $r;
    $info["loginShell"] = "/bin/bash";
    $info["uid"] = $firstname;
       
    $r = ldap_add($ldapconn,  $dn, $info);
    echo 'RESULTAT DU ADD: ' . $r . '<br />';
	}
	
	//
	function modifyPassword($ldapconn, $userUID, $newPasswd){
		$filter="uid=".$userUID;
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$userDn = $info[0]["dn"];
		$entry[userPassword] = $newPasswd; 
		$r = ldap_mod_add($ldapconn,$userDn, $entry); 
		echo 'RESULTAT DU CHGMT DE PASSWD: ' . $r . '<br />';
	}
	//ok
	function addGroup($ldapconn, $groupcn, $memberUid){
			// TODO Link à Formulaire l'entrée des donnees
		$r = rand(1000,9999);
		$dn = "cn=".$groupcn.",ou=group, dc=bla,dc=com";
		$info["objectClass"][0] = "top";
		$info["objectClass"][1] = "posixGroup";
		$info["memberUid"] = $memberUid;
		$info["cn"] = $groupcn;
		$info["gidNumber"] = $r;	
		$r = ldap_add($ldapconn,  $dn, $info);
		echo 'RESULTAT DU ADD: ' . $r . '<br />';
	}
	//OK
	function addUserToGroup($ldapconn, $userUID, $groupCn){
		
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", "cn=".$groupCn); 
		$rank = ldap_count_entries($ldapconn,$sr) - 1; 
         
		$dn = "cn=".$groupCn.",dc=bla,dc=com";
		$entry["memberUid"][$rank] = $userUID;
		$r = ldap_mod_add($ldapconn,$dn, $entry); 
		echo 'RESULTAT DU ADDUSERTOGROUP: ' . $r . '<br />';
	}
	
	//LISTING
	// Utilisation: affichage tableau avec tous les utilisateurs
	//OK
	function listAllUsers($ldapconn){
			    
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", "uid=*"); 
   
		echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn,$sr) 
         . '<br />';

		$info = ldap_get_entries($ldapconn, $sr);

		for ($i=0; $i<$info["count"]; $i++) {
			echo 'dn  : ' . $info[$i]["dn"] . '<br />';
			echo 'premiere entree cn : ' . $info[$i]["cn"][0] . '<br />';
			echo 'name : ' . $info[$i]["givenname"][0] . '<br />';
		}
	
	}
		
	// Utilisation: bouton tout supprimer	
	function deleteAllUsers($ldapconn){
		
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", "uid=*"); 
   
		echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn,$sr) 
         . '<br />';

		$info = ldap_get_entries($ldapconn, $sr);

		for ($i=0; $i<$info["count"]; $i++) {
			deleteUser($ldapconn, $info[$i]["uid"]);
		}	
	}	
	
	function listAllGroups($ldapconn){
	
		echo "---------------------------------------------------------";
		$sr=ldap_search($ldapconn, "ou=group, dc=bla, dc=com", "gidNumber=*"); 
   
		echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn,$sr) 
         . '<br />';

		$info = ldap_get_entries($ldapconn, $sr);
		//echo "<pre>"; print_r($info) ;echo"</pre>";
		for ($i=0; $i<$info["count"]; $i++) {
			echo $info[$i]["cn"][0] . '<br />';
		
	}
}
	
	function listAllGroupsWhereUser($ldapconn, $userUID){
		
		$filter="memberUid=".$userUID;
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", $filter); 
		echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn,$sr) 
         . '<br />';
		$info = ldap_get_entries($ldapconn, $sr);
		for ($i=0; $i<$info["count"]; $i++) {
			echo 'dn  : ' . $info[$i]["givenname"]["dn"] . '<br />';
		}
	}
	
	// Fonction admin
	function deleteUser($ldapconn, $userUID){
	
		$filter="uid=".$userUID;
		$sr=ldap_search($ldapconn, "ou=people,dc=bla,dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$userDn = $info[0]["dn"];

		$filter2="memberUid=".$userUID;
		$sr=ldap_search($ldapconn, "ou=group,dc=bla,dc=com", $filter); 
		echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn,$sr) . '<br />';
		$info = ldap_get_entries($ldapconn, $sr);

		for ($i=0; $i<$info["count"]; $i++) {
			$info[$i]["memberUID"] = $userUID;
			$r = ldap_mod_del($ldapconn, $info[$i]["dn"] );
			echo 'Le résultat de la suppression est ' . $r . '<br />';
			  //unset($info[$i]["memberUID"][$rank]);
		}
		
		$r = ldap_delete ($ldapconn ,$userDn );
		echo 'Le résultat de la suppression est ' . $r . '<br />';
	}
	
	function deleteUserOld($ldapconn, $userUID){
	
		$filter="uid=".$userUID;
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$userDn = $info[0]["dn"];

		
		$r = ldap_delete ($ldapconn ,$userDn );
		echo 'Le résultat de la suppression est ' . $r . '<br />';
	}
	
	//Fonction admin
	function deleteGroup($ldapconn, $groupCn){
		$filter="cn=".$groupCn;
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$groupDn = $info[0]["dn"];
		echo "<pre>"; print_r($info) ;echo"</pre>";
		$r = ldap_delete ($ldapconn ,$groupDn );
		echo 'Le résultat de la suppression est ' . $r . '<br />';
		
	}
	

	
	//TODO MODIFY			  //unset($info[$i]["memberUID"][$rank]);

	
	//TODO IMPORT/EXPORT CSV/JSON
	
	
/*	
5.12/ Ajouter d'un groupe et liÃ© une entrÃ©e avec
# cat ~/group.ldif 
dn: cn=linux,ou=group,dc=bla,dc=com
cn: linux
gidNumber: 1200
memberUid: ivrogne
objectClass: top
objectClass: posixGroup
* 
*/

	
	
	function getLdapConnect(){
		return ldap_connect("localhost");
	}	
	
	
	
	


// Eléments d'authentification LDAP
	$ldaprdn  = 'cn=admin,dc=bla,dc=com';     // DN ou RDN LDAP
	$ldappass = 'bla';  // Mot de passe associé
 
// Connexion au serveur LDAP
	$ldapconn = ldap_connect("localhost")
		or die("Impossible de se connecter au serveur LDAP.");
 
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	echo $ldapconn;

	if ($ldapconn) {
		echo 'Liaison ...'; 
		$r=ldap_bind($ldapconn,"cn=admin,dc=bla,dc=com","bla");     // connexion anonyme, typique
																	// pour un accès en lecture seule.
		echo 'Le résultat de connexion est ' . $r . '<br />';
        if ($r){
			echo "Connexion LDAP réussie...";
		} 
		else {
        echo "Connexion LDAP échouée...";
		}
		 //addUser($ldapconn);
		//deleteUser($ldapconn);
		//listAllUsers($ldapconn);
		//addGroup($ldapconn);
		listAllGroups($ldapconn);
		
		//echo 'Fermeture de la connexion';
		
		//modifyPassword($ldapconn, "john2", "test");
		//deleteGroup($ldapconn, "paumesdelavie");
		//addUserToGroup($ldapconn, "john", "paumesdelavie");
		//listAllGroupsWhereUser($ldapconn, "ivrogne");
		//deleteUser($ldapconn, "ivrogne");
		//ldap_close($ldapconn);
    } 
    else {
        echo "Connexion LDAP échouée...";
	}
	
	
	
/*function connect($ldapconn,$ldaprdn,$ldappass){
	echo 'Liaison ...'; 
    $r=ldap_bind($ldapconn,$ldaprdn,$ldappass);     // connexion anonyme, typique
                                     // pour un accès en lecture seule.
    echo 'Le résultat de connexion est ' . $r . '<br />';
}

function disconnect($ldapconn){
	echo 'Fermeture de la connexion';
    ldap_close($ldapconn);
}
*/


 //connect($ldapconn);
 //disconnect($ldapconn);
 
?>
