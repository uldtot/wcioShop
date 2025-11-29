<header class="main-header fixed-top">
	<nav class="navbar navbar-light bg-white border-bottom navbar-expand justify-content-between flex-column flex-lg-row">

      <div class="col-md-2">
            	<a href="/admin/" class="navbar-brand">
			<i class="fa fa-shopping-bag"></i> {$settingStoreUrl}
		</a>
      </div>
      <div class="col-md-5">
            <form action="#" class="search" _lpchecked="1">
            			<div class="input-group w-100">
            			    <input type="text" class="form-control" placeholder="Search" onkeyup="showResult(this.value)">
					     <div id="livesearch"></div>
            			    <div class="input-group-append">
            			      <button class="btn btn-primary" type="submit">
            			        <i class="fa fa-search"></i>
            			      </button>
            			    </div>
            		    </div>
            		</form>


				<script type="text/javascript">
				function showResult(str) {
		  if (str.length<=3) {
		    document.getElementById("livesearch").innerHTML="";
		    document.getElementById("livesearch").style.border="0px";
		    document.getElementById("livesearch").style.background="transparent";
		    return;
		  }
		  var xmlhttp=new XMLHttpRequest();
		  xmlhttp.onreadystatechange=function() {
		    if (this.readyState==4 && this.status==200) {
		      document.getElementById("livesearch").innerHTML=this.responseText;
		      document.getElementById("livesearch").style.border="1px solid #A5ACB2";
		      document.getElementById("livesearch").style.background="#fff";
		    }
		  }
		  xmlhttp.open("GET","wcio_liveSearch.php?q="+str,true);
		  xmlhttp.send();
		}
				</script>

				<style>
				div#livesearch {
				position: absolute;
				width: 100%;
				top: 46px;
				border-radius: 5px;
				padding: 8px;
				z-index:1;
				}
				</style>

      </div>
      <div class="col-md-5 text-right">
		<div>
			<a class="btn btn-light d-none d-lg-inline-block" href="?logout=1"> Logout </a>
			</div>
      </div>
	</nav>
</header>

<div class="container">
<!-- ========================= SECTION MAIN  ========================= -->
<section class="section-main padding-y">
<main class="">
	<div class="">

<div class="row">
	<aside class="col-md-3 mt-5 card-body"  style="background: #fff;   border: 1px solid #e4e4e4;">
		<nav class="nav-home-aside">
                  <h6 class="title-category">Store</h6>
			<ul class="menu-category">
				<li><a href="/admin/"><i class="fa fa-home"></i> Dashboard</a></li>
				<li><a href="/admin/wcio_orders.php"><i class="fa fa-box"></i> Orders</a></li>
				<li><a href="/admin/wcio_categories.php"><i class="fa fa-bars"></i> Categories</a></li>
				<li><a href="/admin/wcio_products.php"><i class="fa fa-tag"></i> Products</a></li>
				<li><a href="/admin/wcio_pages.php"><i class="fa fa-copy"></i> Pages</a></li>
				<li><a href="/admin/wcio_media.php?folder=/uploads"><i class="fa fa-image"></i> File manager</a></li>
				<!--<li><a href="/admin/wcio_customers.php"><i class="fa fa-user"></i> Customers</a></li>-->
			</ul><br>
                  <h6 class="title-category">Setting & apps</h6>
                  <ul class="menu-category">
				<li class="has-submenu"><a href="/admin/wcio_settings.php"><i class="fa fa-cog"></i> Settings</a></a>
					<ul class="submenu">
						<li><a href="/admin/wcio_settings.php">Store settings</a></li>
						 {section name=setting loop=$wcioShopAdminSettingsMenu}
							<li><a href="/admin/wcio_settings.php?setting={$wcioShopAdminSettingsMenu[setting].url}">{$wcioShopAdminSettingsMenu[setting].columnNiceName}</a></li>
						 {/section}
					</ul>
				</li>

                        <li><a href="/admin/wcio_apps.php"><i class="fa fa-store"></i> Apps</a></li>
			</ul><br>

                  <h6 class="title-category">Get help</h6>
                  <ul class="menu-category">
				<li><a href="https://github.com/websitecareio/wcioShop" target="_blank"><i class="fa fa-store"></i> Support</a></li>
				<!-- <li class="has-submenu"><a href="#">More items</a>
					<ul class="submenu">
						<li><a href="#">Submenu name</a></li>
						<li><a href="#">Great submenu</a></li>
						<li><a href="#">Another menu</a></li>
						<li><a href="#">Some others</a></li>
					</ul>
                        -->
				</li>
			</ul>
		</nav>
	</aside> <!-- col.// -->
