<?php
    include "connect_ldap.php";
    $pwdtxt = $_POST['passwd'];
    $newPassword = '"' . $pwdtxt . '"';
    $newPass = iconv( 'UTF-8', 'UTF-16LE', $newPassword );
    $dn="uid=jones,ou=people,dc=ulco,dc=fr";
    $info["cn"] = "John Jones";
    $info["sn"] = "Jones";
    $info["givenName"] = "Jones";
    $info["userPassword"] = $newPass;
    $info["homeDirectory"] = "/home/jones";
    $info["uidNumber"] = rand(10000,30000);
    $info["gidNumber"] = rand(10000,30000);
    $info["loginShell"] = "/bin/bash";
    $info["uid"] = "jones";
    $info["objectclass"][0] = "top";
    $info["objectclass"][1] = "person";
    $info["objectclass"][2] = "organizationalPerson";
    $info["objectclass"][3] = "inetOrgPerson";
    $info["objectclass"][4] = "posixAccount";
    $info["objectclass"][5] = "shadowAccount";
 
    // Ajoute les données au dossier
    $r = ldap_add($ldapconn, $dn, $info);
    dap_close($ldapconn);
?>
