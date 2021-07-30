{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}

<!-- ========================= SECTION INTRO ========================= -->
<section class="section-intro">

<div class="intro-banner-wrap text-center" style="height: 375px;overflow: hidden;">
	<img src="/templates/default/images/banners/1200x375-dummyimage.png" class="img-fluid">
</div>

</section>
<!-- ========================= SECTION INTRO END// ========================= -->

<!-- ========================= SECTION  ========================= -->
<section class="section-name  padding-y-sm">
<div class="container">

<div class="row">

	<div class="col-md-12"><h2>Featured products</h2></div>
	 {section name=product loop=$displayRandomProducts}
	<div class="col-md-3">
		<div href="{$displayRandomProducts[product].url}" class="card card-product-grid">
			<a href="{$displayRandomProducts[product].url}" class="img-wrap"> <img src="/uploads/{$displayRandomProducts[product].image}"> </a>
			<figcaption class="info-wrap">
				<a href="{$displayRandomProducts[product].url}" class="title" style="height:45px;">{$displayRandomProducts[product].name}</a>
				{if isset($settingCatalogMode)}
					{if $settingCatalogMode != "1"}
					<div class="price mt-1">{{{$displayRandomProducts[product].price}+{$displayRandomProducts[product].price}*20/100}|number_format:2:",":"."} kr.</div> <!-- price-wrap.// -->
					{/if}
				{/if}
			</figcaption>
		</div>
	</div> <!-- col.// -->
	{/section}

</div> <!-- row.// -->
<div class="row">

	<div class="col-md-12">
		{$pageContent}
	</div> <!-- col.// -->


</div> <!-- row.// -->

</div><!-- container // -->

</section>
<!-- ========================= SECTION  END// ========================= -->
{include file='template-parts/footer.tpl'}
