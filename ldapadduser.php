<html>
<head>
<link rel = "stylesheet" type = "text/css" href = "css/user.css">

</head?
<body>
<h1> Adding LDAP User</h1>
<hr>
<?php
$cn = htmlspecialchars($_POST['username']);
$givenName = htmlspecialchars($_POST['firstname']);
$surname = htmlspecialchars($_POST['lastname']);

echo "Adding user: $cn " . '<br>';
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option( $ds, LDAP_OPT_REFERRALS, 0 );
if ($ds) {
$r = ldap_bind($ds,"cn=admin,dc=bla,dc=com","bla");
$info["cn"] = $cn;
$info["givenName"] = $givenName;
$info['objectclass'][0] = "top";
$info['objectclass'][1] = "person";
$info['objectclass'][2] = "inetOrgPerson";
$r = ldap_add($ds,"cn=$cn,ou=Users,dc=bla,dc=com",$info);
$sr = ldap_search($ds,"dc=bla,dc=com,cn=$cn");
$info = ldap_get_entries($ds,$sr);
echo "The user:<span class='result'> " . $info[0]["dn"] . "</span> has been created. <br>";
}
ldap_close($ds);
?>
<hr>
<a href = "ldapadduser.html">Add another user</a>
</body>
</html>
