<html>
	<head>
	<link rel = "stylesheet" type = "text/css" href = "css/user.css">
	<title>Add ldap user</title>
		<body>
			<?php include('connect/connect.php') ?>
		<h1>Add User</h1>
		<hr>
		
		<?php
		echo $ldapconn;
		if (isset($_POST['firstname'],$_POST['lastname'],$_POST['pwd'])){
		 addUser($ldapconn,$_POST['firstname'],$_POST['lastname'],$_POST['pwd']);
		 }?>
			<form action = "ldapTest.php" method = "post">
				<p>
					<label for = "firstname">First Name:</label> <input class = "input" type ="text" id = "firstname" name="firstname" >
					<br><label for="lastname">Last Name:</label> <input class="input" type ="text" id = "lastname" name="lastname">
					<br><label for="pwd">pwd:</label> <input type="text" class="input" id="pwd" name="pwd">
					<br><label>&nbsp</label><input type="submit" value="submit" class="button">
				</p>
			</form>
			
	
	<h1>Delete User</h1>
	<hr>
		<?php if(isset($_POST['firstname2'])){ deleteUser($ldapconn,$_POST['firstname2']); }?>
				
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="username">Username:</label> <input type="text" class="input" id="firstname2" name="firstname2">
				<br><label>&nbsp</label><input type="submit" value="submit" class="button">
			</p>
		</form>
		
		<h1>Add Group</h1>
	<hr>
		<?php if(isset($_POST['groupCn'], $_POST['memberUid'])){
			addGroup($ldapconn,$_POST['groupCn'], $_POST['memberUid'] );
			}?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="groupCn">groupCn:</label> <input type="text" class="input" id="groupCn" name="groupCn">
				<br><label for="memberUid">memberUid:</label> <input type="text" class="input" id="memberUid" name="memberUid">
				<br><label>&nbsp</label><input type="submit" value="submit" class="button">
			</p>
		</form>
		
		<h1>Delete Group</h1>
	<hr>
		<?php if(isset($_POST['groupCn2'])){
			deleteGroup($ldapconn,$_POST['groupCn2']);
			}?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="groupCn2">groupCn:</label> <input type="text" class="input" id="groupCn2" name="groupCn2">
				<br><label>&nbsp</label><input type="submit" value="submit" class="button">
			</p>
		</form>
				
		<h1>Add User to Group</h1>
	<hr>
		<?php if(isset($_POST['userUID'],$_POST['groupCn3'])){
			addUserToGroup($ldapconn,$_POST['userUID'],$_POST['groupCn3']);
			}?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="userUID">userUID:</label> <input type="text" class="input" id="userUID" name="userUID">
				<br><label for="groupCn3">groupCn:</label> <input type="text" class="input" id="groupCn3" name="groupCn3">
				<br><label>&nbsp</label><input type="submit" value="submit" class="button">
			</p>
		</form>
		
		
	<h1>Modify Password</h1>
	<hr>
		<?php if(isset($_POST['userUID'],$_POST['newPassword'])){
			modifyPassword($ldapconn,$_POST['userUID'],$_POST['newPassword']);
			}?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="userUID">userUID:</label> <input type="text" class="input" id="userUID" name="userUID">
				<br><label for="newPassword">Nouveau mdp:</label> <input type="text" class="input" id="newPassword" name="newPassword">
				<br><label>&nbsp</label><input type="submit" value="submit" class="button">
			</p>
		</form>
		
		
		
		<h1>Modify User (admin)</h1>
	<hr>
		<?php 
			modifyUser($ldapconn, $_POST['userUID'],$_POST['firstname'], $_POST['lastname'],$_POST['pwd'], $_POST['homedir']);
			?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="userUID">userUID:</label> <input type="text" class="input" id="userUID" name="userUID">
				<br><label for="firstname">firstname</label> <input type="text" class="input" id="firstname" name="firstname">
				<br><label for="lastname">lastname</label> <input type="text" class="input" id="lastname" name="lastname">
				<br><label for="pwd">pwd</label> <input type="text" class="input" id="pwd" name="pwd">
				<br><label for="homedir">homedir</label> <input type="text" class="input" id="homedir" name="homedir">
				<br><label>&nbsp</label><input type="submit" value="submit" class="button">
			</p>
		</form>
		
		
		<?php 
					
					if($_POST['btn'] == "delU"){
						deleteAllUsers($ldapconn);
					}
					else if ($_POST['btn'] == "delA"){
						deleteAllGroups($ldapconn);
					}
			?>
		<form action = "ldapTest.php" method = "post">
		<button  type="submit" id="delUser" onclick="delUser" name="btn" value="delU"> Delete All Users </button>
		<button type="submit" id="delGroup" onclick="delGroup" name="btn" value="delA"> Delete All Groups </button>
		</form>
		
	</body>
	</head>
</html>


