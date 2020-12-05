{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-9 mt-5">
      <div class="card card-body">
<h1>Products</h2>

            <!--Table-->
            <table id="tablePreview" class="table">
            <!--Table head-->
              <thead>
                <tr>
                  <th>Active</th>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Stock</th>
                  <th>Price</th>
                  <th>Sku</th>
                  <th>View</th>
                </tr>
              </thead>
              <!--Table head-->
              <!--Table body-->
              <tbody>
                   {section name=product loop=$wcioShopAdminProducts}
                         <tr>
                          <td>{if $wcioShopAdminProducts[product].active == "0"}
                                <i class="fas fa-circle" style="color:#74b816; font-size:12px;"></i>
                                {else}
                                <i class="fas fa-circle" style="color:#dd1b16;font-size:12px;"></i>
                                {/if}</td>
                          <td><img src="/uploads/{$wcioShopAdminProducts[product].image}" style="width:38px;"></a></td>
                          <td>{$wcioShopAdminProducts[product].name}</td>
                          <td>{$wcioShopAdminProducts[product].stock}</td>
                          <td>{$wcioShopAdminProducts[product].price}</td>
                          <th>{$wcioShopAdminProducts[product].partno}</th>
                          <td>
                                <a href="/admin/products/view/?id={$wcioShopAdminProducts[product].id}" target="_blank">Edit</a> |
                                <a href="{$wcioShopAdminProducts[product].image}" target="_blank">View</a>
                          </td>
                        </tr>
                   {/section}

              </tbody>
              <!--Table body-->
            </table>
            <!--Table-->


            </div>
            </div> <!-- col.// -->
            {include file='template-parts/footer.tpl'}
