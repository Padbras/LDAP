
<meta charset="UTF-8">

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function addUser($ldapconn, $firstname, $lastname, $pwd)
{
    $r                      = rand(1000, 9999);
    $dn                     = "uid=" . $firstname . ",ou=people,dc=bla,dc=com";
    $info["objectClass"][0] = "top";
    $info["objectClass"][1] = "person";
    $info["objectClass"][2] = "organizationalPerson";
    $info["objectClass"][3] = "inetOrgPerson";
    $info["objectClass"][4] = "posixAccount";
    $info["objectClass"][5] = "shadowAccount";
    
    $info["sn"]            = $lastname;
    $info["givenName"]     = $firstname;
    $info["cn"]            = $lastname . " " . $firstname;
    $info["userPassword"]  = $pwd;
    $info["homeDirectory"] = "/home/" . $firstname;
    $info["uidNumber"]     = $r;
    $info["gidNumber"]     = $r;
    $info["loginShell"]    = "/bin/bash";
    $info["uid"]           = $firstname;
    
    $r = ldap_add($ldapconn, $dn, $info);
    
}


function importUser($ldapconn, $dn, $sn, $givenname, $cn, $userPassword, $homeDirectory, $uidNumber, $gidNumber, $uid)
{
    
    
    $info["objectClass"][0] = "top";
    $info["objectClass"][1] = "person";
    $info["objectClass"][2] = "organizationalPerson";
    $info["objectClass"][3] = "inetOrgPerson";
    $info["objectClass"][4] = "posixAccount";
    $info["objectClass"][5] = "shadowAccount";
    
    $info["sn"]            = $sn;
    $info["givenName"]     = $givenname;
    $info["cn"]            = $cn;
    $info["userPassword"]  = $userPassword;
    $info["homeDirectory"] = $homeDirectory;
    $info["uidNumber"]     = $uidNumber;
    $info["gidNumber"]     = $gidNumber;
    $info["loginShell"]    = "/bin/bash";
    $info["uid"]           = $uid;
    
    $r = ldap_add($ldapconn, $dn, $info);
    
}

function modifyUser($ldapconn, $userUID, $firstname, $lastname, $pwd, $homedir)
{
    $filter  = "uid=" . $userUID;
    $sr      = ldap_search($ldapconn, " ou=people, dc=bla, dc=com", $filter);
    $entries = ldap_get_entries($ldapconn, $sr);
    $userDn  = $entries[0]["dn"];
    echo $userDn;
    
    $values1["givenName"] = $firstname;
    ldap_modify($ldapconn, $userDn, $values1);
    
    $values2["sn"] = $lastname;
    ldap_modify($ldapconn, $userDn, $values2);
    
    $values3["userPassword"] = $pwd;
    ldap_modify($ldapconn, $userDn, $values3);
    
    $values4["homeDirectory"] = $homedir;
    ldap_modify($ldapconn, $userDn, $values4);
    
    
    
    /*
    if(!empty($firstname){
    $values1["givenName"] = $firstname;
    ldap_modify($ldapconn, $userDn, $values1);
    }
    
    if(!empty($lastname){
    $values2["sn"] = $lastname;
    ldap_modify($ldapconn, $userDn, $values2);
    }
    
    if(!empty($pwd){
    $values3["userPassword"] = $pwd;
    ldap_modify($ldapconn, $userDn, $values3);
    }
    
    if(!empty($homedir){
    $values4["loginShell"] = $homedir;
    ldap_modify($ldapconn, $userDn, $values4);
    }
    */
    
    
    /*    $info["firstname"] = $firstname; 
    $info["lastname"] = $lastname; 
    $info["pwd"] = $pwd; 
    $info["homedir"] = $homedir; */
    
    /*$modif =[
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
    ldap_rename($ldapconn,$userDn,"uid=".$userUID,NULL,TRUE);*/
}

function modifyGroup($ldapconn, $groupCn, $memberUid, $description)
{
    $filter  = "cn=" . $groupCn;
    $sr      = ldap_search($ldapconn, "dc=bla, dc=com", $filter);
    $entries = ldap_get_entries($ldapconn, $sr);
    $userDn  = $entries[0]["dn"];
    echo $userDn;
    
    $values1["memberUid"] = $memberUid;
    ldap_modify($ldapconn, $userDn, $values1);
    
    $values2["description"] = $description;
    ldap_modify($ldapconn, $userDn, $values2);
    
}

function modifyPassword($ldapconn, $userUID, $newPasswd)
{
    $filter                = "uid=" . $userUID;
    $sr                    = ldap_search($ldapconn, " ou=people, dc=bla, dc=com", $filter);
    $info                  = ldap_get_entries($ldapconn, $sr);
    $userDn                = $info[0]["dn"];
    $entry["userPassword"] = $newPasswd;
    $r                     = ldap_mod_add($ldapconn, $userDn, $entry);
}

function addGroup($ldapconn, $groupcn, $memberUid, $description)
{
    $r  = rand(1000, 9999);
    $dn = "cn=" . $groupcn . ",ou=group, dc=bla,dc=com";
    
    
}

function importGroup($ldapconn, $dn, $groupcn, $memberUid, $description, $gidNumber)
{
    
    $info["objectClass"][0] = "top";
    $info["objectClass"][1] = "posixGroup";
    $info["memberUid"]      = $memberUid;
    $info["description"]    = $description;
    $info["cn"]             = $groupcn;
    $info["gidNumber"]      = $gidNumber;
    $r                      = ldap_add($ldapconn, $dn, $info);
}

function addUserToGroup($ldapconn, $userUID, $groupCn)
{
    
    $sr                        = ldap_search($ldapconn, "dc=bla, dc=com", "cn=" . $groupCn);
    $rank                      = ldap_count_entries($ldapconn, $sr) - 1;
    $dn                        = "cn=" . $groupCn . ",ou=group,dc=bla,dc=com";
    $entry["memberUid"][$rank] = $userUID;
    echo $entry;
    echo $dn;
    $r = ldap_mod_add($ldapconn, $dn, $entry);
}

function listAllUsers($ldapconn)
{
    
    $sr = ldap_search($ldapconn, "ou=people, dc=bla, dc=com", "uid=*");
    
    echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn, $sr) . '<br />';
    
    $info = ldap_get_entries($ldapconn, $sr);
    
    for ($i = 0; $i < $info["count"]; $i++) {
        
        echo $info[$i]["dn"];
        echo '<br />';
        echo $info[$i]["sn"][0];
        echo '<br />';
        echo $info[$i]["givenname"][0];
        echo '<br />';
        echo $info[$i]["cn"][0];
        echo '<br />';
        echo $info[$i]["userpassword"][0];
        echo '<br />';
        echo $info[$i]["homedirectory"][0];
        echo '<br />';
        echo $info[$i]["uidnumber"][0];
        echo '<br />';
        echo $info[$i]["gidnumber"][0];
        echo '<br />';
        echo $info[$i]["uid"][0];
        echo '<br />';
        
    }
}


function generateJson($ldapconn)
{
    
    $sr   = ldap_search($ldapconn, "ou=people, dc=bla, dc=com", "uid=*");
    $info = ldap_get_entries($ldapconn, $sr);
    
    /*for ($i=0; $i<$info["count"]; $i++) {
    echo json_encode($info[$i]);
    }*/
    
    for ($i = 0; $i < $info["count"]; $i++) {
        $dn            = $info[$i]["dn"];
        $sn            = $info[$i]["sn"][0];
        $givenname     = $info[$i]["givenname"][0];
        $cn            = $info[$i]["cn"][0];
        $userPassword  = $info[$i]["userpassword"][0];
        $homeDirectory = $info[$i]["homedirectory"][0];
        $uidNumber     = $info[$i]["uidnumber"][0];
        $gidNumber     = $info[$i]["gidnumber"][0];
        $uid           = $info[$i]["uid"][0];
        
        
        $people[] = array(
            'dn' => $dn,
            'sn' => $sn,
            'givenname' => $givenname,
            'cn' => $cn,
            'userPassword' => $userPassword,
            'homeDirectory' => $homeDirectory,
            'uidNumber' => $uidNumber,
            'gidNumber' => $gidNumber,
            'uid' => $uid
        );
    }
    
    $sr = ldap_search($ldapconn, "ou=group, dc=bla, dc=com", "gidNumber=*");
    
    $info = ldap_get_entries($ldapconn, $sr);
    for ($i = 0; $i < $info["count"]; $i++) {
        $dn          = $info[$i]["dn"];
        $cn          = $info[$i]["cn"][0];
        $memberUid   = $info[$i]["memberuid"][0];
        $description = $info[$i]["description"][0];
        $gidNumber   = $info[$i]["gidnumber"][0];
        
        $groups[] = array(
            'dn' => $dn,
            'cn' => $cn,
            'memberUid' => $memberUid,
            'description' => $description,
            'gidNumber' => $gidNumber
        );
        
    }
    $response['people'] = $people;
    $response['groups'] = $groups;
    //echo $response;
    $fp                 = fopen('/var/www/html/connect/results.json', 'w');
    fwrite($fp, json_encode($response, JSON_PRETTY_PRINT));
    fclose($fp);
    
}


function printUserJson($ldapconn)
{
    
    $string    = file_get_contents("/var/www/html/connect/results.json");
    $json_data = json_decode($string, true);
    
    foreach ($json_data["people"] as &$people) {
        importUser($ldapconn, $people["dn"], $people["sn"], $people["givenname"], $people["cn"], $people["userPassword"], $people["homeDirectory"], $people["uidNumber"], $people["gidNumber"], $people["uid"]);
    }
    foreach ($json_data["groups"] as &$group) {
        importGroup($ldapconn, $group["dn"], $group["cn"], $group["memberUid"], $group["description"], $group["gidNumber"]);
    }
}

function deleteAllUsers($ldapconn)
{
    
    $sr = ldap_search($ldapconn, "ou=people, dc=bla, dc=com", "uidNumber=*");
    
    
    $info = ldap_get_entries($ldapconn, $sr);
    
    for ($i = 0; $i < $info["count"]; $i++) {
        deleteUser($ldapconn, $info[$i]["uid"][0]);
    }
}

function deleteAllGroups($ldapconn)
{
    
    $sr   = ldap_search($ldapconn, "ou=group, dc=bla, dc=com", "gidNumber=*");
    $info = ldap_get_entries($ldapconn, $sr);
    
    for ($i = 0; $i < $info["count"]; $i++) {
        
        deleteGroup($ldapconn, $info[$i]["cn"][0]);
    }
    
}

function listAllGroups($ldapconn)
{
    
    $sr = ldap_search($ldapconn, "ou=group, dc=bla, dc=com", "gidNumber=*");
    
    $info = ldap_get_entries($ldapconn, $sr);
    for ($i = 0; $i < $info["count"]; $i++) {
        echo $info[$i]["dn"] . '<br />';
        echo $info[$i]["cn"][0] . '<br />';
        echo $info[$i]["memberuid"][0] . '<br />';
        echo $info[$i]["description"][0] . '<br />';
        echo $info[$i]["gidnumber"][0] . '<br />';
        
    }
}

function listAllGroupsWhereUser($ldapconn, $userUID)
{
    
    $filter = "memberUid=" . $userUID;
    $sr     = ldap_search($ldapconn, "ou=group, dc=bla, dc=com", $filter);
    echo 'nombre d\'entrées  :' . ldap_count_entries($ldapconn, $sr) . '<br />';
    $info = ldap_get_entries($ldapconn, $sr);
    for ($i = 0; $i < $info["count"]; $i++) {
        echo 'dn  : ' . $info[$i]["dn"] . '<br />';
    }
}

function deleteUser($ldapconn, $userUID)
{
    
    $filter               = "uid=" . $userUID;
    $sr                   = ldap_search($ldapconn, "ou=people,dc=bla,dc=com", $filter);
    $info                 = ldap_get_entries($ldapconn, $sr);
    $userDn               = $info[0]["dn"];
    $filter2              = "memberUid=" . $userUID;
    $sr                   = ldap_search($ldapconn, "ou=group,dc=bla,dc=com", $filter2);
    $info                 = ldap_get_entries($ldapconn, $sr);
    $entry["memberUid"][] = $userUID;
    
    for ($i = 0; $i < $info["count"]; $i++) {
        $r = ldap_mod_del($ldapconn, $info[$i]["dn"], $entry);
        echo $info[$i]["dn"];
    }
    $r = ldap_delete($ldapconn, $userDn);
}



function deleteGroup($ldapconn, $groupCn)
{
    $filter  = "cn=" . $groupCn;
    $sr      = ldap_search($ldapconn, "dc=bla, dc=com", $filter);
    $info    = ldap_get_entries($ldapconn, $sr);
    $groupDn = $info[0]["dn"];
    $r       = ldap_delete($ldapconn, $groupDn);
    
}





function getLdapConnect()
{
    return ldap_connect("localhost");
}



$ldaprdn  = 'cn=admin,dc=bla,dc=com';
$ldappass = 'bla';

$ldapconn = ldap_connect("localhost") or die("Impossible de se connecter au serveur LDAP.");

ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
echo $ldapconn;

if ($ldapconn) {
    echo 'Liaison ...';
    $r = ldap_bind($ldapconn, "cn=admin,dc=bla,dc=com", "bla");
    if ($r) {
        echo "Connexion LDAP réussie...";
    } else {
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
} else {
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
