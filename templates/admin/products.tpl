{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-9 mt-5">
      <div class="card card-body">
<h1>Products</h1><a href="?action=add">Add new product</a>

            <!--Table-->
            <table id="tablePreview" class="table">
            <!--Table head-->
              <thead>
                <tr>
                  <th>Active</th>
                  <th>Image</th>
                  <th>Name</th>
                  <th>View</th>
                </tr>
              </thead>
              <!--Table head-->
              <!--Table body-->
              <tbody>
                   {section name=product loop=$wcioShopAdminProducts}
               
                         <tr>
                          <td>{if $wcioShopAdminProducts[product].active == "1"}
                                <i class="fas fa-circle" style="color:#74b816; font-size:12px;"></i>
                                {else}
                                <i class="fas fa-circle" style="color:#dd1b16;font-size:12px;"></i>
                                {/if}</td>
                          <td><img src="/uploads/{$wcioShopAdminProducts[product].images}" style="width:38px;"></a></td>
                          <td>{$wcioShopAdminProducts[product].name}</td>
                          <td>
                                <a href="wcio_products.php?id={$wcioShopAdminProducts[product].prdid}&action=edit">Edit</a> |
                                <a href="{$wcioShopAdminProducts[product].url}" target="_blank">View</a>  |
                                 <a href="/admin/wcio_products.php?action=delete&id={$wcioShopAdminProducts[product].prdid}" 
                                onclick="return confirm('Are you sure you want to delete this product?');">
                                Delete
                             </a>
                             
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
