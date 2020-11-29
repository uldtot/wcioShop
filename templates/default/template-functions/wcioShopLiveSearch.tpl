
	{section name=product loop=$wcioShopLiveSearch}
	<div class="row mb-2">
		<div class="col-md-3 d-none d-sm-block">
			<a href="{$wcioShopLiveSearch[product].url}" class="img-wrap"> <img src="/uploads/{$wcioShopLiveSearch[product].image}"> </a>
		</div>
		<div class="col-md-9">
		     <div href="{$wcioShopLiveSearch[product].url}" class="">
			     <figcaption class="info-wrap">
				     <a href="{$wcioShopLiveSearch[product].url}" class="title" style="height:45px;">{$wcioShopLiveSearch[product].name}</a>
				     <div class="price mt-1">{{{$wcioShopLiveSearch[product].price}+{$wcioShopLiveSearch[product].price}*20/100}|number_format:2:",":"."} kr.</div> <!-- price-wrap.// -->
			     </figcaption>
		     </div>
		</div> <!-- col.// -->
	</div>
	{/section}
