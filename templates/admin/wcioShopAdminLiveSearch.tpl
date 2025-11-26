{if $liveSearchOrders}
      <h3>Orders</h3>
      <ul>
      {section name=order loop=$liveSearchOrders}
            <li><a href="{$liveSearchOrders[order].url}">#{$liveSearchOrders[order].id} from {$liveSearchOrders[order].firstname} {$liveSearchOrders[order].lastname}</li></a>
      {/section}
      </ul>
{/if}

{if $liveSearchProducts}
<h3>Products</h3>
<ul>
      {section name=prodcut loop=$liveSearchProducts}
            <li><a href="{$liveSearchProducts[prodcut].url}">{if $liveSearchProducts[prodcut].active == "1"}<i class="fas fa-circle" style="color:#74b816; font-size:12px;"></i>{else}<i class="fas fa-circle" style="color:#dd1b16;font-size:12px;"></i>{/if} {$liveSearchProducts[prodcut].name}</li></a>
      {/section}
</ul>
{/if}
{if $liveSearchSettings}
<h3>Settings</h3>
<ul>
      <li><a href="#">Random setting</li></a>
      <li><a href="#">Random setting</li></a>
      <li><a href="#">Random setting</li></a>
</ul>
{/if}
{if $liveSearchApps}
<h3>Apps</h3>
<ul>
      <li><a href="#">Test app</li></a>
      <li><a href="#">Test app</li></a>
</ul>
{/if}
