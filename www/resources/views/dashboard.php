<?php include_once('../../../lib/User.php'); session_start();?>
<div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar" ng-controller='SidebarController as sidebar'>
            <li ng-repeat="item in sidebar.items"><a href="{{item.id}}">{{item.name}}</a></li>
          </ul>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Dashboard</h1>

       <p>Lorem Ipsem</p>
       <p><?php var_dump($_SESSION); ?>
		<h1 class="page-header">Encryption Test</h1>
		<p>Plaintext: amuchlongerstringthatistobeencrypted</p>
		<p>Ciphertext: <?php
			$ciphertext = $_SESSION['user']->encryptServerPassword('amuchlongerstringthatistobeencrypted');
			echo $ciphertext;
			?>
		</p>
		<p>Decrypted: <?php echo $_SESSION['user']->decryptServerPassword($ciphertext);
		?>
		</p>
</div>