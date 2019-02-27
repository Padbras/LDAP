function ListeLdap()
{
    // Variables de connection
     
    $Conf_LDAP_Server    = "ldap://".'****.yyyy.yyy';   // "MyServer.MyDomain";
    $Conf_Def_Dom        = 'yyyy.yyy';          // "MyDomain";
    $FiltreSearch   = "(&(objectClass=user)(objectCategory=person)(sn=*))";
    $search                 = "OU=****,DC=****,DC=***";
 
    $ds = @ldap_connect($Conf_LDAP_Server);
    if ($ds)
    {
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
         
        $r = ldap_bind($ds, $_SESSION['LDAP_USER'].'@'.$Conf_Def_Dom, $_SESSION['LDAP_PW']);     // connexion avec ses login/mdp
 
        // Recherche
        $sr=ldap_search($ds, $search, $FiltreSearch);
 
        $infoLDAP = ldap_get_entries($ds, $sr);
 
        ldap_close($ds);
         
        $info = array(); $nom = array(); $groupe = array();
         
        for($i=0,$j=0 ; $i<count($infoLDAP) ; $i++,$j++)
        {
            if( !empty($infoLDAP[$i]['sn'][0]) && !empty($infoLDAP[$i]['givenname'][0]) && !empty($infoLDAP[$i]['memberof'][0]) )
            {
                $grp = explode(',', $infoLDAP[$i]['memberof'][0]);
                $grp2 = substr($grp[0], 3);
                $info[$j]['groupe']   = $groupe[] = utf8_encode($grp2);
                $info[$j]['nom']    = $nom[] = utf8_encode($infoLDAP[$i]['sn'][0]);
                $info[$j]['prenom'] = utf8_encode($infoLDAP[$i]['givenname'][0]);
                $info[$j]['mail']   = utf8_encode($infoLDAP[$i]['userprincipalname'][0]);
                 
            } else $j--;
        }
        array_multisort($groupe, SORT_ASC, $nom, SORT_ASC, $info);
         
        return $info;
 
    } else return false;
}
