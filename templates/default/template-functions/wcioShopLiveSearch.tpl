{section name=product loop=$wcioShopLiveSearch}
<a href="{$wcioShopLiveSearch[product].url}" class="livesearch-item d-flex align-items-center mb-2">

    <!-- IMAGE -->
    <div class="item-image mr-2">
        <img src="/uploads/{$wcioShopLiveSearch[product].image}" alt="{$wcioShopLiveSearch[product].name}">
    </div>

    <!-- TEXT INFO -->
    <div class="item-info flex-grow-1">
        <div class="item-title">{$wcioShopLiveSearch[product].name}</div>

        {if isset($settingCatalogMode)}
        {if $settingCatalogMode != "1"}
        <div class="item-price">
            {{{$wcioShopLiveSearch[product].price}+{$wcioShopLiveSearch[product].price}*20/100}|number_format:2:",":"."} kr.
        </div>
        {/if}
        {/if}
    </div>

</a>
{/section}
