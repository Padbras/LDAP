
<meta charset="UTF-8">

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

	function addUser($ldapconn,$firstname,$lastname,$pwd){
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
    
	}
	
	function modifyUser($ldapconn, $userUID ,$firstname, $lastname, $pwd, $homedir){
		$filter="uid=".$userUID;
		$sr=ldap_search($ldapconn, " ou=people, dc=bla, dc=com", $filter); 
		$entries = ldap_get_entries($ldapconn, $sr);
		$userDn = $entries[0]["dn"];
		echo $userDn;
		
		/*	$info["firstname"] = $firstname; 
			$info["lastname"] = $lastname; 
			$info["pwd"] = $pwd; 
			$info["homedir"] = $homedir; */
			
		$modif =[
			[
				"attrib" =>"firstname",
				"modtype" => LDAP_MODIFY_BATCH_REPLACE,
				"values"  => [$firstname],
			],
						[
				"attrib" =>"lastname",
				"modtype" => LDAP_MODIFY_BATCH_REPLACE,
				"values"  => [$lastname],
			],
						[
				"attrib" =>"pwd",
				"modtype" => LDAP_MODIFY_BATCH_REPLACE,
				"values"  => [$pwd],
			],
						[
				"attrib" =>"homedir",
				"modtype" => LDAP_MODIFY_BATCH_REPLACE,
				"values"  => [$homedir],
			],
		];
		
		$r = ldap_modify_batch($ldapconn,$userDn, $modif); 
		ldap_rename($ldapconn,$userDn,"uid=".$userUID,NULL,TRUE);
	}
	
	function modifyPassword($ldapconn, $userUID, $newPasswd){
		$filter="uid=".$userUID;
		$sr=ldap_search($ldapconn, " ou=people, dc=bla, dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$userDn = $info[0]["dn"];
		$entry["userPassword"] = $newPasswd; 
		$r = ldap_mod_add($ldapconn,$userDn, $entry); 
	}

	function addGroup($ldapconn, $groupcn, $memberUid, $description){
		$r = rand(1000,9999);
		$dn = "cn=".$groupcn.",ou=group, dc=bla,dc=com";
		$info["objectClass"][0] = "top";
		$info["objectClass"][1] = "posixGroup";
		$info["memberUid"] = $memberUid;
		$info["description"] = $description; 
		$info["cn"] = $groupcn;
		$info["gidNumber"] = $r;	
		$r = ldap_add($ldapconn,  $dn, $info);
		
	}

	function addUserToGroup($ldapconn, $userUID, $groupCn){
		
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", "cn=".$groupCn); 
		$rank = ldap_count_entries($ldapconn,$sr) - 1; 
		$dn = "cn=".$groupCn.",ou=group,dc=bla,dc=com";
		$entry["memberUid"][$rank] = $userUID;
		echo $entry; 
		echo $dn; 
		$r = ldap_mod_add($ldapconn,$dn, $entry); 
	}
	
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
		
	function deleteAllUsers($ldapconn){
		
		$sr=ldap_search($ldapconn, "ou=people, dc=bla, dc=com", "uidNumber=*"); 
   

		$info = ldap_get_entries($ldapconn, $sr);

		for ($i=0; $i<$info["count"]; $i++) {
			deleteUser($ldapconn, $info[$i]["uid"][0]);
		}	
	}	
	
	function deleteAllGroups($ldapconn){
		
		$sr=ldap_search($ldapconn, "ou=group, dc=bla, dc=com", "gidNumber=*"); 
		$info = ldap_get_entries($ldapconn, $sr);

		for ($i=0; $i<$info["count"]; $i++) {
			
			deleteGroup($ldapconn, $info[$i]["cn"][0]);
		}	
		
	}
	
	function listAllGroups($ldapconn){
	
		$sr=ldap_search($ldapconn, "ou=group, dc=bla, dc=com", "gidNumber=*"); 
   
		$info = ldap_get_entries($ldapconn, $sr);
		for ($i=0; $i<$info["count"]; $i++) {
			echo $info[$i]["cn"][0] . '<br />';
		
	}
}
	
	function listAllGroupsWhereUser($ldapconn, $userUID){
		
		$filter="memberUid=".$userUID;
		$sr=ldap_search($ldapconn, "ou=group, dc=bla, dc=com", $filter); 
		echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn,$sr) 
         . '<br />';
		$info = ldap_get_entries($ldapconn, $sr);
		for ($i=0; $i<$info["count"]; $i++) {
			echo 'dn  : ' . $info[$i]["dn"] . '<br />';
		}
	}
	
	function deleteUser($ldapconn, $userUID){
	
		$filter="uid=".$userUID;
		$sr=ldap_search($ldapconn, "ou=people,dc=bla,dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$userDn = $info[0]["dn"];
		$filter2="memberUid=".$userUID;
		$sr=ldap_search($ldapconn, "ou=group,dc=bla,dc=com", $filter2); 
		$info = ldap_get_entries($ldapconn, $sr);
		$entry["memberUid"][]=$userUID; 
			
		for ($i=0; $i<$info["count"]; $i++) {
			$r = ldap_mod_del($ldapconn, $info[$i]["dn"], $entry );
			echo $info[$i]["dn"];
		}
		$r = ldap_delete ($ldapconn ,$userDn );
	}
	
	
	
	function deleteGroup($ldapconn, $groupCn){
		$filter="cn=".$groupCn;
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$groupDn = $info[0]["dn"];
		$r = ldap_delete ($ldapconn ,$groupDn );
		
	}
	


	
	
	function getLdapConnect(){
		return ldap_connect("localhost");
	}	
	
	
	
	$ldaprdn  = 'cn=admin,dc=bla,dc=com';     
	$ldappass = 'bla'; 
 
	$ldapconn = ldap_connect("localhost")
		or die("Impossible de se connecter au serveur LDAP.");
 
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	echo $ldapconn;

	if ($ldapconn) {
		echo 'Liaison ...'; 
		$r=ldap_bind($ldapconn,"cn=admin,dc=bla,dc=com","bla");    															
        if ($r){
			echo "Connexion LDAP réussie...";
		} 
		else {
        echo "Connexion LDAP échouée...";
		}
		/*
		echo "USERS:"; 
		listAllUsers($ldapconn);
		echo "GROUPS:"; 
		listAllGroups($ldapconn);
		echo "GROUPS DE PESTELLE:"; 
		listAllGroupsWhereUser($ldapconn, "Corentin");
		*/
		
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

	
	//TODO MODIFY		

	
	//TODO IMPORT/EXPORT CSV/JSON
	
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
  
  /*
   * Admin: change pas cn, dn, uid (peut changer sn, givenname, userPassword, homeDirectory)
   * Users: Password
   * 
   * Admin: peut modifier groupe (DESCRIPTION et memberUid)
   * 
  */
  
  /*function deleteUserOld($ldapconn, $userUID){
	
		$filter="uid=".$userUID;
		$sr=ldap_search($ldapconn, "dc=bla, dc=com", $filter); 
		$info = ldap_get_entries($ldapconn, $sr);
		$userDn = $info[0]["dn"];

		
		$r = ldap_delete ($ldapconn ,$userDn );
		echo 'Le résultat de la suppression est ' . $r . '<br />';
	}*/
	
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

 //connect($ldapconn);
 //disconnect($ldapconn);
 
?>
