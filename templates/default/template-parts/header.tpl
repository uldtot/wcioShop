{$maintenanceMode}

<header class="section-header">

<section class="header-main border-bottom">
	<div class="container">
<div class="row align-items-center">
	<div class="col-lg-5 col-4">
		<h3>{$settingStoreName}</h3> <!-- brand-wrap.// -->
		<span>{$settingStoreSlogan}</span> <!-- brand-wrap.// -->
	</div>

	<div class="col-lg-7 col-sm-6 col-12">
		<div class="widgets-wrap float-md-right">
			<div class="widget-header  mr-3">
				<a href="/cart/" class="icon icon-sm rounded-circle border"><i class="fa fa-shopping-cart"></i></a>
				<span class="badge badge-pill badge-danger notify">{nocache}{if $headerCart.numberOfItems != "0"}{$headerCart.numberOfItems}{/if}{/nocache}</span>
			</div>
		</div> <!-- widgets-wrap.// -->
	</div> <!-- col.// -->
</div> <!-- row.// -->
	</div> <!-- container.// -->
</section> <!-- header-main .// -->
</header> <!-- section-header.// -->
