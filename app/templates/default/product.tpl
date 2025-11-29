{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}

<!-- ========================= SECTION PAGETOP ========================= -->
<section class="section-pagetop bg">
<div class="container">
	<h1 class="title-page">{$wcioDisplayProduct.name}</h1>
	{if isset($productBreadcrumbs)}
		{if $productBreadcrumbs != ""}
		<nav>
		<ol class="breadcrumb text-white">
		    <li class="breadcrumb-item"><a href="#">Home</a></li>
		    <li class="breadcrumb-item"><a href="#">Best category</a></li>
		    <li class="breadcrumb-item active" aria-current="page">Great articles</li>
		</ol>
		</nav>
		{/if}
	{/if}
</div> <!-- container //  -->
</section>
<!-- ========================= SECTION INTRO END// ========================= -->

<!-- ========================= SECTION CONTENT ========================= -->
<section class="section-content padding-y">
<div class="container">

	<div class="row">
		<div class="card" style="width: 100%;">
	<div class="row no-gutters">
		<aside class="col-sm-6 border-right">
<article class="gallery-wrap">
	<div class="img-big-wrap">
	   {if isset($nothinghere)}<a href="#">{/if}
		   <img src="/uploads/{$wcioDisplayProduct.image}">
	  {if isset($nothinghere)} </a>{/if}
	</div> <!-- img-big-wrap.// -->
	{if isset($nothinghere)}
	<div class="thumbs-wrap">
	  <a href="#" class="item-thumb"> <img src="https://dummyimage.com/600x400/000/fff"></a>
	  <a href="#" class="item-thumb"> <img src="https://dummyimage.com/600x400/000/fff"></a>
	  <a href="#" class="item-thumb"> <img src="https://dummyimage.com/600x400/000/fff"></a>
	  <a href="#" class="item-thumb"> <img src="https://dummyimage.com/600x400/000/fff"></a>
	</div> <!-- thumbs-wrap.// -->
	{/if}
</article> <!-- gallery-wrap .end// -->
		</aside>
		<main class="col-sm-6">
<article class="content-body">
    
	<div class="rating-wrap mb-3" style="display:none;">
		<span class="badge badge-warning"> <i class="fa fa-star"></i> 3.8</span>
		<small class="text-muted ml-2">45 reviews</small>
	</div>
	<p>{$wcioDisplayProduct.excerpt}</p>

{if isset($nothinghere)}
<div class="item-option-select" style="display:none;">
	<h6>Model</h6>
	<div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
	  <label class="btn btn-light active">
	    <input type="radio" name="radio_size" checked=""> Xs
	  </label>
	  <label class="btn btn-light">
	    <input type="radio" name="radio_size"> Xs Max
	  </label>
	</div>
</div>

<div class="item-option-select" style="display:none;">
	<h6>Color</h6>
	<div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
	  <label class="btn btn-light">
	    <input type="radio" name="radio_color"> Silver
	  </label>
	  <label class="btn btn-light active">
	    <input type="radio" name="radio_color" checked=""> Gray
	  </label>
	  <label class="btn btn-light">
	    <input type="radio" name="radio_color"> Gold
	  </label>
	</div>
</div>

<div class="item-option-select" style="display:none;">
	<h6>Capacity</h6>
	<div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
	  <label class="btn btn-light active">
	    <input type="radio" name="options" checked=""> 64 GB
	  </label>
	  <label class="btn btn-light">
	    <input type="radio" name="options"> 256 GB
	  </label>
	  <label class="btn btn-light">
	    <input type="radio" name="options"> 512 GB
	  </label>
	</div>
</div>
{/if}

<div class="row mt-3 align-items-center">
	{if isset($settingStoreCatalogMode)}
		{if $settingStoreCatalogMode != "1"}
			<div class="col">
			<span class="price h4">$815.00</span>
		</div> <!-- col.// -->
		<div class="col text-left">

			<select class="form-control mb-3 mt-3">
		  		<option> 1 </option>
		  		<option> 2 </option>
		  		<option> 3 </option>
		  	</select>
			
			<a href="#" class="btn  btn-light"> <i class="fas fa-heart"></i>  </a>
			<a href="#" class="btn  btn-light"> <i class="fa fa-folder-plus"></i>  </a>
		</div> <!-- col.// -->
			{else}	
			
		
		
		{/if}
	{/if}
</div> <!-- row.// -->

</article> <!-- product-info-aside .// -->
		</main> <!-- col.// -->
	</div> <!-- row.// -->
</div>

	</div> <!-- row.// -->

	<div class="row mt-5">
		<div class="col-md-12"><h3>Product information</h3></div>
	</div>
	<div class="row mt-2">
		<div class="col-md-12">
			<article class="card">
				<div class="card-body">
						<h4>{$wcioDisplayProduct.name}</h4>
						{$wcioDisplayProduct.meta.fullDescription}
				</div> <!-- card-body.// -->
			</article>
		</div>
	</div>


</div> <!-- container //  -->

</section>



{include file='template-parts/footer.tpl'}
