{$maintenanceMode}

<header class="section-header">

<section class="header-main border-bottom">
	<div class="container">
<div class="row align-items-center">
	<div class="col-lg-4 col-4">
		<a href="/">
			<h3 class="storeName">{$settingStoreName}</h3> <!-- brand-wrap.// -->
			<span class="storeSlogan">{$settingStoreSlogan}</span> <!-- brand-wrap.// -->
		</a>
	</div>

	<div class="col-lg-4 col-sm-12">
		<form action="#" class="search" _lpchecked="1">
			<div class="input-group w-100">
			    <input type="text" class="form-control" placeholder="{_('Search')}" onkeyup="showResult(this.value)">
			  <div id="livesearch"></div>
			    <div class="input-group-append">
			      <button class="btn btn-primary" type="submit">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		    </div>
		</form> <!-- search-wrap .end// -->


		<script type="text/javascript">
		function showResult(str) {
  if (str.length<=3) {
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("livesearch").innerHTML=this.responseText;

	if(this.responseText.trim() != "") {
      	document.getElementById("livesearch").style.border="1px solid #A5ACB2";
	}

    }
  }
  xmlhttp.open("GET","/liveSearch/?q="+str,true);
  xmlhttp.send();
}
		</script>

		<style>
		div#livesearch {
		position: absolute;
		width: 100%;
		top: 46px;
		background: #fff;
		border-radius: 5px;
		padding: 8px;
		z-index:1;
		}
		</style>


	</div>

	{if isset($settingStoreCatalogMode)}
    	{if $settingStoreCatalogMode != "1"}
    	<div class="col-lg-4 col-sm-6 col-12">
    		<div class="widgets-wrap float-md-right">
    			<div class="widget-header  mr-3">
    				<a href="/cart/" class="icon icon-sm rounded-circle border"><i class="fa fa-shopping-cart"></i></a>
    				<span class="badge badge-pill badge-danger notify">{nocache}{if isset($headerCart.numberOfItems)}{if $headerCart.numberOfItems != "0"}{$headerCart.numberOfItems}{/if}{/if}{/nocache}</span>
    			</div>
    		</div> <!-- widgets-wrap.// -->
    	</div> <!-- col.// -->
    	{/if}
	{/if}
</div> <!-- row.// -->
	</div> <!-- container.// -->
</section> <!-- header-main .// -->

<nav class="navbar navbar-main navbar-expand-lg navbar-light">
  <div class="container">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav" aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="main_nav">
      <ul class="navbar-nav">
	  {section name=category loop=$navigationStyleCategories}
	  	{if $navigationStyleCategories[category].url != ""}
			  <li class="nav-item categoryId-{$navigationStyleCategories[category].id}">
			    <a class="nav-link" href="{$navigationStyleCategories[category].url}">{$navigationStyleCategories[category].name}</a>
			  </li>
	  	{/if}
	  {/section}
      </ul>
    </div> <!-- collapse .// -->
  </div> <!-- container .// -->
</nav>

</header> <!-- section-header.// -->
