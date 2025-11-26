{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}

<!-- ========================= SECTION  ========================= -->
<section class="section-name  padding-y-sm">
<div class="container">

<div class="row">

	<div class="col-md-12"></div>
	 {section name=product loop=$displayRandomProducts}
	<div class="col-md-3">
		<div href="{$displayRandomProducts[product].url}" class="card card-product-grid">
			<a href="{$displayRandomProducts[product].url}" class="img-wrap"> <img src="/uploads/{$displayRandomProducts[product].image}"> </a>
			<figcaption class="info-wrap">
				<a href="{$displayRandomProducts[product].url}" class="title" style="height:45px;">{$displayRandomProducts[product].name}</a>
				{if isset($settingStoreCatalogMode)}
					{if $settingStoreCatalogMode != "1"}

                      {assign var="price" value=$displayRandomProducts[product].price}
                      {assign var="vat" value=$settingStoreVat / 100}
                      {assign var="totalPrice" value=$price + ($price * $vat)}

                      <div class="price mt-1">{if $settingStoreCurrencyPlacement == left}{$settingStoreCurrencies}{/if} {$totalPrice|number_format:$settingStoreDefaultCurrencyFormat} {if $settingStoreCurrencyPlacement == right}{$settingStoreCurrencies}{/if}</div> <!-- price-wrap.// -->

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
