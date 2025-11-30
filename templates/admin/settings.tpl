{include file='template-parts/head.tpl'}
{include file='template-parts/header.tpl'}


<div class="col-md-9 mt-5">
      <div class="card card-body">
<h1>Store settings</h1>
      <p>Here you can edit all store settings</p>

</div>

      <!-- Looping settingMainGroup -->
               {foreach from=$wcioShopAdminSettings key=k item=i}

                              {foreach from=$i key=k1 item=i1}
                              
                            
                              <div class="card card-body mt-3">
                                    <h3>{$k1}</h3>
                                    <form method="post" action="/admin/settings.php">
                                    <input type="hidden" name="save" value="1">
                                          <!--Table-->
                                          <table id="tablePreview" class="table">
                                          <!--Table head-->
                                            <thead>
                                              <tr>
                                             
                                                <th>Setting name</th>
                                                <th>Value</th>
                                              </tr>
                                            </thead>
                                            <!--Table head-->
                                            <!--Table body-->
                                            <tbody>

                                                  {foreach from=$i1 key=k2 item=i2}
                                                        <tr>
                                                        
                                                         <td style="width: 50%"><b>{$i2.columnNiceName}</b><br>
                                                         <p>{$i2.columnDescription}</p></td>
                                                         <td>
                                                               {if $i2.columnType == 'textarea'}
                                                               <textarea id="w3review" name="{$i2.id}" rows="4" cols="50" class="form-control">{$i2.columnValue}</textarea>
                                                               {elseif $i2.columnType == 'text'}
                                                               <input type="text" name="{$i2.id}" class="form-control" value="{$i2.columnValue}">
                                                               {elseif $i2.columnType == 'onoff'}
                                                               <select name="{$i2.id}" class="form-control" >
                                                                     <option value="0" {if $i2.columnValue == 0}checked{/if}>No</option>
                                                                     <option value="1" {if $i2.columnValue == 1}checked{/if}>Yes</option>
                                                               </select>
                                                               {else}
                                                                <input type="text" name="{$i2.id}" class="form-control {$i2.columnName}" value="{$i2.columnValue}">
                                                               {/if}
                                                         </td>
                                                      </tr>
                                                  {/foreach}

                                                  <tr>
                                                       <td colspan="3" class="text-right"><button type="submit" class="btn btn-primary">Save "{$k1}"</button></td>
                                                  </tr>

                                            </tbody>
                                            <!--Table body-->
                                          </table>
                                          <!--Table-->
                                    </form>
                              </div>
                              {/foreach}

               {/foreach}



</div> <!-- col.// -->
{include file='template-parts/footer.tpl'}
