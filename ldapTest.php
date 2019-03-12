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
		<?php if(isset($_POST['groupCn'], $_POST['memberUid'], $_POST['description'])){
			addGroup($ldapconn,$_POST['groupCn'], $_POST['memberUid'], $_POST['description'] );
			}?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="groupCn">groupCn:</label> <input type="text" class="input" id="groupCn" name="groupCn">
				<br><label for="memberUid">memberUid:</label> <input type="text" class="input" id="memberUid" name="memberUid">
				<br><label for="description">description:</label> <input type="text" class="input" id="description" name="description">
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
		
		<?php if(isset($_POST['userUID2'],$_POST['firstname2'],$_POST['lastname2'], $_POST['pwd2'], $_POST['homedir2'])){
			modifyUser($ldapconn, $_POST['userUID2'],$_POST['firstname2'], $_POST['lastname2'],$_POST['pwd2'], $_POST['homedir2']);
			}
			?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="userUID2">userUID:</label> <input type="text" class="input" id="userUID2" name="userUID2">
				<br><label for="firstname2">firstname</label> <input type="text" class="input" id="firstname2" name="firstname2">
				<br><label for="lastname2">lastname</label> <input type="text" class="input" id="lastname2" name="lastname2">
				<br><label for="pwd2">pwd</label> <input type="text" class="input" id="pwd2" name="pwd2">
				<br><label for="homedir2">homedir</label> <input type="text" class="input" id="homedir2" name="homedir2">
				<br><label>&nbsp</label><input type="submit" value="submit" class="button">
			</p>
		</form>
		
	

<h1>Modify Group (admin)</h1>
	<hr>
		
		<?php if(isset($_POST['groupCn'],$_POST['memberUid'],$_POST['description'])){
			modifyGroup($ldapconn, $_POST['groupCn'],$_POST['memberUid'], $_POST['description']);
			}
			?>
		<form action = "ldapTest.php" method = "post">
			<p>
				<label for="groupCn">groupCn:</label> <input type="text" class="input" id="groupCn" name="groupCn">
				<br><label for="memberUid">memberUid</label> <input type="text" class="input" id="memberUid" name="memberUid">
				<br><label for="description">description</label> <input type="text" class="input" id="description" name="description">
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

<?php 
					
					if($_POST['btn'] == "genJson"){
						generateJson($ldapconn);
					}
					
			?>
		<form action = "ldapTest.php" method = "post">
		<button type="submit" id="genJson" onclick="genJson" name="btn" value="genJson"> Generate JSon </button>
		</form>

<?php 
					
					if($_POST['btn'] == "printUserJson"){
						printUserJson($ldapconn);
					}
					
			?>
		<form action = "ldapTest.php" method = "post">
		<button type="submit" id="printUserJson" onclick="printUserJson" name="btn" value="printUserJson"> Import Json </button>
		</form>


<?php 
					
					if($_POST['btn'] == "listAllUsers"){
						listAllUsers($ldapconn);
					}
					
			?>
		<form action = "ldapTest.php" method = "post">
		<button type="submit" id="listAllUsers" onclick="listAllUsers" name="btn" value="listAllUsers"> listAllUsers  </button>
		</form>


<?php 
					
					if($_POST['btn'] == "listAllGroups"){
						listAllGroups($ldapconn);
					}
					
			?>
		<form action = "ldapTest.php" method = "post">
		<button type="submit" id="listAllGroups" onclick="listAllGroups" name="btn" value="listAllGroups"> listAllGroups  </button>
		</form>

	


		
	</body>
	</head>
</html>


