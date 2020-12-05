{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-6 mt-5">
      <div class="card card-body">
<h1>Order #{$wcioShopAdminOrdersView.cart_id} details</h2>

      <h5>General</h5>
            <div><span class="font-weight-bold">Created at:</span> {$wcioShopAdminOrdersView.dateandtime}</div>
            <div><span class="font-weight-bold">Shipping method:</span> {$wcioShopAdminOrdersView.shippingmethod}</div>
            <div><span class="font-weight-bold">Payment method:</span> {$wcioShopAdminOrdersView.paymentmethod}</div>
<br>
<div class="row">
<div class="col-md-6 mt-2">
      <h5>Customer</h5>
            <p>
                  {$wcioShopAdminOrdersView.firstname} {$wcioShopAdminOrdersView.lastname}<br>
                  {$wcioShopAdminOrdersView.email}<br>
                  {$wcioShopAdminOrdersView.phone}<br>
            </p>
</div>
<div class="col-md-6 mt-2">
      <h5>Shipping adress</h5>
            <p>
                  {$wcioShopAdminOrdersView.adress}<br>
                  {$wcioShopAdminOrdersView.zip}, {$wcioShopAdminOrdersView.city}<br>
            </p>
</div>
</div>


      </div>

      <div class="card card-body mt-3">
            <table class="table table-borderless table-shopping-cart">
                  <thead class="text-muted">
                  <tr class="small text-uppercase">
                    <th scope="col">Product</th>
                    <th scope="col" width="120">Quantity</th>
                    <th scope="col" width="120">Price</th>
                  </tr>
                  </thead>
                  <tbody>



                  {section name=product loop=$wcioShopAdminOrdersViewProducts}
                  <tr>
                  	<td>
                  		<figure class="itemside align-items-center">
                  			<div class="aside"><img src="/uploads/{$wcioShopAdminProducts[product].image}" class="img-sm"></div>
                  			<figcaption class="info">
                  				<a href="/admin/products/view/?d={$wcioShopAdminOrdersViewProducts[product].prdid}" class="title text-dark">{$wcioShopAdminOrdersViewProducts[product].name}</a>
                  				<!-- <p class="text-muted small">Matrix: 25 Mpx <br> Brand: Canon</p>-->
                  			</figcaption>
                  		</figure>
                  	</td>
                  	<td>
                  		{$wcioShopAdminOrdersViewProducts[product].amount}
                  	</td>
                  	<td>
                  		<div class="price-wrap">
                  			<var class="price">{if $settingStoreCurrencyPlacement == "left"}{$settingStoreShownDefaultCurrency}{/if} {{$wcioShopAdminOrdersViewProducts[product].price * $wcioShopAdminOrdersViewProducts[product].amount} * ($wcioShopAdminOrdersViewProducts[product].vat / 100) + $wcioShopAdminOrdersViewProducts[product].price * $wcioShopAdminOrdersViewProducts[product].amount} {if $settingStoreCurrencyPlacement == "right"}{$settingStoreShownDefaultCurrency}{/if}</var>
                  			<small class="text-muted">{if $settingStoreCurrencyPlacement == "left"}{$settingStoreShownDefaultCurrency}{/if} {($wcioShopAdminOrdersViewProducts[product].price * $wcioShopAdminOrdersViewProducts[product].vat / 100) + $wcioShopAdminOrdersViewProducts[product].price} {if $settingStoreCurrencyPlacement == "right"}{$settingStoreShownDefaultCurrency}{/if} each </small>
                  		</div> <!-- price-wrap .// -->
                  	</td>
                  </tr>
                  {/section}

                  </tbody>
                  </table>

                  <table class="table table-borderless table-shopping-cart">
                        <thead class="text-muted">
                        <tr class="small text-uppercase">
                          <th scope="col" width="220"></th>
                          <th scope="col" width="120"></th>
                        </tr>
                        </thead>
                        <tbody>
                              <tr>
                                    <td>Shipping (including VAT):</td>
                                    <td>{if $settingStoreCurrencyPlacement == "left"}{$settingStoreShownDefaultCurrency}{/if} {$wcioShopAdminOrdersView.cart_shipping * $wcioShopAdminOrdersView.cart_shipping_vat / 100 + $wcioShopAdminOrdersView.cart_shipping} {if $settingStoreCurrencyPlacement == "right"}{$settingStoreShownDefaultCurrency}{/if}</td>
                              </tr>
                              <tr>
                                    <td>Fees:</td>
                                    <td>{if $settingStoreCurrencyPlacement == "left"}{$settingStoreShownDefaultCurrency}{/if} {$wcioShopAdminOrdersView.cart_fees} {if $settingStoreCurrencyPlacement == "right"}{$settingStoreShownDefaultCurrency}{/if}</td>
                              </tr>
                              {if $wcioShopAdminOrdersView.cart_discount != "" && $wcioShopAdminOrdersView.cart_discount != "0"}
                              <tr>
                                    <td>Discounts:</td>
                                    <td>{if $settingStoreCurrencyPlacement == "left"}{$settingStoreShownDefaultCurrency}{/if} {$wcioShopAdminOrdersView.cart_discount} ( {$wcioShopAdminOrdersView.cart_discount_used} ) {if $settingStoreCurrencyPlacement == "right"}{$settingStoreShownDefaultCurrency}{/if}</td>
                              </tr>
                              {/if}
                              <tr>
                                    <td><b>Order Total  (including VAT):</b></td>
                                    <td><b>{if $settingStoreCurrencyPlacement == "left"}{$settingStoreShownDefaultCurrency}{/if} {$wcioShopAdminOrdersViewProductsTotal - {$wcioShopAdminOrdersView.cart_discount} + {$wcioShopAdminOrdersView.cart_shipping * $wcioShopAdminOrdersView.cart_shipping_vat / 100 + $wcioShopAdminOrdersView.cart_shipping} + $wcioShopAdminOrdersView.cart_fees + $wcioShopAdminOrdersViewProductsVat} {if $settingStoreCurrencyPlacement == "right"}{$settingStoreShownDefaultCurrency}{/if}</b></td>
                              </tr>
                              <tr>
                                    <td>Order VAT:</td>
                                    <td>{if $settingStoreCurrencyPlacement == "left"}{$settingStoreShownDefaultCurrency}{/if} {$wcioShopAdminOrdersViewProductsVat + {$wcioShopAdminOrdersView.cart_shipping * $wcioShopAdminOrdersView.cart_shipping_vat / 100 }} {if $settingStoreCurrencyPlacement == "right"}{$settingStoreShownDefaultCurrency}{/if}
                                    </td>
                              </tr>
                        </tbody>
                  </table>
      </div>
</div> <!-- col.// -->

<div class="col-md-3 mt-5">
      <div class="col-md-12">
            <div class="card card-body">
            <h3>Order actions</h3>
            <form>
                  <select name="50" class="form-control">
                        <option value="0">Choose an action...</option>
                        <option value="send_order_details">Email invoice / order details to customer</option>
                        <option value="send_order_details_admin">Resend new order notification to admin</option>
                  </select>
                   <a class="btn btn-primary d-none d-lg-inline-block mt-2 float-right" href=""> Do action </a>
            </form>

            </div>
      </div>
      <div class="col-md-12 mt-3">
            <div class="card card-body">
            <h3>Order status</h3>
            <p><span class="font-weight-bold">Status:</span> {$wcioShopAdminOrdersView.cart_status}</p>
            <form>
                  <select name="50" class="form-control">
                        <option value="wc-pending" {if $wcioShopAdminOrdersView.cart_status == "pending"}selected{/if}>Pending payment</option>
                        <option value="wc-processing" {if $wcioShopAdminOrdersView.cart_status == "processing"}selected{/if}>Processing</option>
                        <option value="wc-on-hold" {if $wcioShopAdminOrdersView.cart_status == "on-hold"}selected{/if}>On hold</option>
                        <option value="wc-completed" {if $wcioShopAdminOrdersView.cart_status == "completed"}selected{/if}>Completed</option>
                        <option value="wc-cancelled" {if $wcioShopAdminOrdersView.cart_status == "cancelled"}selected{/if}>Cancelled</option>
                        <option value="wc-refunded" {if $wcioShopAdminOrdersView.cart_status == "refunded"}selected{/if}>Refunded</option>
                        <option value="wc-failed" {if $wcioShopAdminOrdersView.cart_status == "failed"}selected{/if}>Failed</option>
                   </select>
                   <a class="btn btn-primary d-none d-lg-inline-block mt-2 float-right" href=""> Update status </a>
            </form>
            </div>
      </div>
      <div class="col-md-12 mt-3">
            <div class="card card-body">
            <h3>Shipping information</h3>
            <div><span class="font-weight-bold">Total shipping weight:</span> {$wcioShopAdminOrdersViewProductsShippingWeight} {$settingStoreWeightType}</div>
            </div>
      </div>
      <div class="col-md-12 mt-3">
            <div class="card card-body">
            <h3>Order notes</h3>
            <p>{$wcioShopAdminOrdersView.notes}</p>
            </div>
      </div>
      <div class="col-md-12 mt-3">
            <div class="card card-body">
            <h3>Admin notes</h3>
            <form>
                  <div><span class="font-weight-bold">Stock updated:</span> {if $wcioShopAdminOrdersView.StockUpdated == "1"}Yes{else}No{/if}</div>
                  <div><span class="font-weight-bold">Notes:</span><br>
                        <textarea rows="5" cols="30"  class="form-control">
                              {$wcioShopAdminOrdersView.AdminNotes}
                        </textarea>


                        <a class="btn btn-primary d-none d-lg-inline-block mt-2 float-right" href=""> Update note </a>
                  </div>
            </form>
            </div>
      </div>

</div>
{include file='template-parts/footer.tpl'}
